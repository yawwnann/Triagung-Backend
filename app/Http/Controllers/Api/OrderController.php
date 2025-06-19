<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Tymon\JWTAuth\Facades\JWTAuth;

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

        $order = \App\Models\Order::create([
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
}