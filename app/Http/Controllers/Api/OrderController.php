<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Tymon\JWTAuth\Facades\JWTAuth;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function myOrders(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $orders = Order::with('items')->where('user_id', $user->id)->orderByDesc('created_at')->get();
        return response()->json($orders);
    }

    public function cart(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $cart = Order::with('items')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get();
        return response()->json($cart);
    }

    public function storeCart(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:produks,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $addressId = $request->input('address_id') ?? $user->addresses()->first()?->id;
        if (!$addressId) {
            return response()->json(['error' => 'User belum memiliki alamat.'], 422);
        }

        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $addressId,
            'order_number' => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'total_amount' => 0,
            'shipping_cost' => 0,
            'tax' => 0,
            'discount' => 0,
            'grand_total' => 0,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => null,
            'notes' => null,
        ]);

        $total = 0;
        foreach ($request->items as $item) {
            $produk = \App\Models\Produk::findOrFail($item['product_id']);
            $qty = $item['quantity'];
            $subtotal = $produk->harga * $qty;
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $produk->id,
                'product_name' => $produk->nama,
                'price' => $produk->harga,
                'quantity' => $qty,
                'subtotal' => $subtotal,
            ]);
            $total += $subtotal;
        }
        $order->update([
            'total_amount' => $total,
            'grand_total' => $total,
        ]);

        return response()->json($order->load('items'), 201);
    }

    public function updateCartItem(Request $request, $itemId)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $item = \App\Models\OrderItem::whereHas('order', function ($q) use ($user) {
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
        $order->total_amount = $order->items()->sum('subtotal');
        $order->grand_total = $order->total_amount;
        $order->save();

        return response()->json($item->fresh(['order']));
    }

    public function deleteCartItem(Request $request, $itemId)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $item = \App\Models\OrderItem::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id)->where('status', 'pending');
        })->findOrFail($itemId);
        $order = $item->order;
        $item->delete();
        // Update grand_total order
        $order->total_amount = $order->items()->sum('subtotal');
        $order->grand_total = $order->total_amount;
        $order->save();
        return response()->json(['success' => true]);
    }

    // API checkout cart
    public function checkout(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
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
}