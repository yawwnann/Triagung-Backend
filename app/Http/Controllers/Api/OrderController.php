<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function myOrders(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $orders = Order::with('items')->where('user_id', $user->id)->where('status', '!=', 'pending')->orderByDesc('created_at')->get();
        return response()->json($orders);
    }

    public function cart(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $cart = Order::with('items.produk')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        return response()->json($cart);
    }

    public function storeCart(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $request->validate([
            'product_id' => 'required|exists:produks,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Cari atau buat keranjang baru (order dengan status 'pending')
        $cart = Order::firstOrCreate(
            [
                'user_id' => $user->id,
                'status' => 'pending',
            ],
            [
                'address_id' => $user->addresses()->where('is_default', true)->first()?->id,
                'order_number' => 'CART-' . strtoupper(Str::random(8)),
                'payment_status' => 'pending',
                'total_amount' => 0,
                'shipping_cost' => 0,
                'tax' => 0,
                'discount' => 0,
                'grand_total' => 0,
            ]
        );

        $produk = Produk::findOrFail($request->product_id);

        // Cek apakah item sudah ada di keranjang
        $orderItem = $cart->items()->where('product_id', $produk->id)->first();

        if ($orderItem) {
            // Jika sudah ada, update kuantitasnya
            $orderItem->quantity += $request->quantity;
            $orderItem->subtotal = $orderItem->quantity * $orderItem->price;
            $orderItem->save();
        } else {
            // Jika belum ada, buat item baru
            $cart->items()->create([
                'product_id' => $produk->id,
                'product_name' => $produk->nama,
                'price' => $produk->harga,
                'quantity' => $request->quantity,
                'subtotal' => $produk->harga * $request->quantity,
            ]);
        }

        // Hitung ulang total keranjang
        $total = $cart->items()->sum('subtotal');
        $cart->total_amount = $total;
        $cart->grand_total = $total + $cart->shipping_cost + $cart->tax - $cart->discount;
        $cart->save();

        return response()->json($cart->load('items.produk'));
    }

    public function updateCartItem(Request $request, $itemId)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $item = OrderItem::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id)->where('status', 'pending');
        })->findOrFail($itemId);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item->quantity = $request->quantity;
        $item->subtotal = $item->price * $request->quantity;
        $item->save();

        // Update grand_total order
        $order = $item->order;
        $total = $order->items()->sum('subtotal');
        $order->total_amount = $total;
        $order->grand_total = $total + $order->shipping_cost + $order->tax - $order->discount;
        $order->save();

        return response()->json($item->fresh(['order.items.produk']));
    }

    public function deleteCartItem(Request $request, $itemId)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $item = OrderItem::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id)->where('status', 'pending');
        })->findOrFail($itemId);
        $order = $item->order;
        $item->delete();

        // Jika keranjang menjadi kosong, hapus keranjang
        if ($order->items()->count() === 0) {
            $order->delete();
            return response()->json(['message' => 'Keranjang berhasil dikosongkan']);
        }

        // Update grand_total order
        $total = $order->items()->sum('subtotal');
        $order->total_amount = $total;
        $order->grand_total = $total + $order->shipping_cost + $order->tax - $order->discount;
        $order->save();

        return response()->json(['success' => true]);
    }

    // API checkout cart
    public function checkout(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'notes' => 'nullable|string|max:1000',
        ]);
        $addressId = $request->address_id;
        // Pastikan address milik user
        $address = $user->addresses()->where('id', $addressId)->first();
        if (!$address) {
            return response()->json(['error' => 'Alamat tidak valid.'], 422);
        }
        // Ambil order pending milik user
        $order = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->first();
        if (!$order) {
            return response()->json(['error' => 'Tidak ada cart yang bisa di-checkout.'], 404);
        }
        // Update address jika berbeda
        if ($order->address_id != $addressId) {
            $order->address_id = $addressId;
        }
        // Update notes jika ada
        if ($request->has('notes')) {
            $order->notes = $request->notes;
        }
        $order->status = 'processing';
        $order->save();

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        // Data untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->grand_total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
        ];
        $snapToken = Snap::getSnapToken($params);
        $order->payment_token = $snapToken;
        $order->save();

        return response()->json([
            'order' => $order->load('items'),
            'snap_token' => $snapToken,
        ]);
    }

    // API untuk melihat detail pesanan
    public function orderDetail(Request $request, $orderId)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $order = Order::with('items')
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();
        if (!$order) {
            return response()->json(['error' => 'Pesanan tidak ditemukan'], 404);
        }
        return response()->json($order);
    }
}