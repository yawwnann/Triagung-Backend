<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;

class WebhookController extends Controller
{
    /**
     * Handle Midtrans payment notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        // 1. Konfigurasi dan validasi notifikasi dasar
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        $notification = new Notification();

        // 2. Ekstrak data penting dari notifikasi
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;
        $paymentType = $notification->payment_type;

        // 3. Cari order di database
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        // 4. Verifikasi signature untuk keamanan
        $signatureKey = hash('sha512', $orderId . $notification->status_code . $notification->gross_amount . config('midtrans.server_key'));
        if ($notification->signature_key != $signatureKey) {
            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        // 5. Update order berdasarkan status notifikasi
        // Hanya proses jika order masih dalam status menunggu pembayaran
        if ($order->payment_status === 'pending') {
            if ($transactionStatus == 'settlement' || ($transactionStatus == 'capture' && $fraudStatus == 'accept')) {
                // Pembayaran berhasil
                $order->payment_status = 'paid';
                $order->payment_method = $paymentType; // <-- Menyimpan metode pembayaran
                // Anda bisa juga mengubah status order jika perlu, misal:
                // $order->status = 'paid';
            } else if ($transactionStatus == 'expire' || $transactionStatus == 'cancel' || $transactionStatus == 'deny') {
                // Pembayaran gagal atau dibatalkan
                $order->payment_status = 'failed';
                $order->status = 'cancelled';
            }
        }

        $order->save();

        return response()->json(['status' => 'success']);
    }
}