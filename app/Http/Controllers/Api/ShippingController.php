<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RajaOngkirService;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShippingController extends Controller
{
    private $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    /**
     * Hitung ongkir untuk keranjang belanja
     */
    public function calculateShipping(Request $request)
    {
        Log::info('ShippingController@calculateShipping', $request->all());
        $request->validate([
            'destination_city_id' => 'required|string',
            'courier' => 'nullable|string|in:jne,pos,tiki',
            'weight' => 'nullable|integer|min:1'
        ]);
        Log::info('DEBUG: Setelah validasi');

        try {
            $user = JWTAuth::parseToken()->authenticate();
            Log::info('DEBUG: Setelah autentikasi', ['user_id' => $user->id]);

            // Ambil keranjang belanja user
            $cart = Order::with('items.produk')
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();
            Log::info('DEBUG: Setelah cek keranjang', ['cart' => $cart]);

            if (!$cart || $cart->items->isEmpty()) {
                Log::info('DEBUG: Keranjang kosong');
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang belanja kosong'
                ], 400);
            }

            $totalWeight = $cart->items()->get()->sum(function ($item) {
                $itemWeight = $item->produk->berat ?? 1000;
                return $itemWeight * $item->quantity;
            });
            Log::info('DEBUG: Setelah hitung berat', ['totalWeight' => $totalWeight]);

            // Gunakan berat dari request atau berat total keranjang
            $weight = $request->weight ?? $totalWeight;
            $courier = $request->courier ?? 'jne';

            Log::info('DEBUG: Sebelum hitung ongkir', [
                'destination_city_id' => $request->destination_city_id,
                'weight' => $weight,
                'courier' => $courier
            ]);

            // Hitung ongkir menggunakan Raja Ongkir
            $shippingResult = $this->rajaOngkirService->getCachedShippingCost(
                $request->destination_city_id,
                $weight,
                $courier
            );
            Log::info('DEBUG: Setelah hitung ongkir', ['shippingResult' => $shippingResult]);

            if (!$shippingResult['success']) {
                Log::info('DEBUG: Ongkir gagal', ['message' => $shippingResult['message']]);
                return response()->json([
                    'success' => false,
                    'message' => $shippingResult['message']
                ], 400);
            }

            // Format response untuk frontend
            $formattedCosts = [];
            foreach ($shippingResult['costs'] as $cost) {
                $formattedCosts[] = [
                    'service' => $cost['service'] ?? $cost['code'] ?? '',
                    'description' => $cost['description'] ?? '',
                    'cost' => $cost['cost'] ?? 0,
                    'etd' => $cost['etd'] ?? '',
                    'note' => $cost['note'] ?? ''
                ];
            }

            Log::info('DEBUG: Sukses hitung ongkir');

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghitung ongkir',
                'data' => [
                    'origin' => $shippingResult['origin'],
                    'destination' => $shippingResult['destination'],
                    'weight' => $weight,
                    'courier' => strtoupper($courier),
                    'costs' => $formattedCosts
                ]
            ]);

        } catch (\Exception $e) {
            Log::info('DEBUG: Exception', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dapatkan daftar kurir yang tersedia
     */
    public function getCouriers()
    {
        $couriers = $this->rajaOngkirService->getAvailableCouriers();

        return response()->json([
            'success' => true,
            'data' => $couriers
        ]);
    }

    /**
     * Update ongkir pada keranjang belanja
     */
    public function updateCartShipping(Request $request)
    {
        $request->validate([
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_service' => 'required|string',
            'shipping_courier' => 'required|string'
        ]);

        try {
            $user = JWTAuth::parseToken()->authenticate();

            $cart = Order::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang belanja tidak ditemukan'
                ], 404);
            }

            // Update ongkir pada keranjang
            $cart->shipping_cost = $request->shipping_cost;
            $cart->shipping_service = $request->shipping_service;
            $cart->shipping_courier = $request->shipping_courier;

            // Hitung ulang grand total
            $total = $cart->items()->sum('subtotal');
            $cart->total_amount = $total;
            $cart->grand_total = $total + $cart->shipping_cost + $cart->tax - $cart->discount;

            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Ongkir berhasil diperbarui',
                'data' => $cart->load('items.produk')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}