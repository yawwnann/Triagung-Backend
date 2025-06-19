<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil hanya user dengan role 'user' (pelanggan)
        $users = User::where('role', 'user')->get();
        $produks = Produk::all();
        if ($produks->isEmpty()) {
            if (method_exists($this, 'command') && $this->command) {
                $this->command->warn('Tidak ada produk, seeder order dilewati.');
            }
            return;
        }
        foreach ($users as $user) {
            $address = $user->addresses()->inRandomOrder()->first();
            for ($i = 1; $i <= 10; $i++) {
                $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                $payment_statuses = ['pending', 'paid', 'failed'];

                // Tentukan bulan secara acak (1-12)
                $month = rand(1, 12);
                $year = now()->year;
                $createdAt = now()->setDate($year, $month, rand(1, 28))->setTime(rand(0, 23), rand(0, 59), rand(0, 59));

                $order = Order::create([
                    'user_id' => $user->id,
                    'address_id' => $address ? $address->id : null,
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'total_amount' => 0,
                    'shipping_cost' => rand(10000, 50000),
                    'tax' => rand(1000, 10000),
                    'discount' => rand(0, 5000),
                    'grand_total' => 0,
                    'status' => $statuses[array_rand($statuses)],
                    'payment_status' => $payment_statuses[array_rand($payment_statuses)],
                    'payment_method' => 'transfer',
                    'notes' => 'Pesanan dummy',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
                $total = 0;
                for ($j = 1; $j <= rand(2, 5); $j++) {
                    $produk = $produks->random();
                    $qty = rand(1, 10); // variasikan quantity
                    $subtotal = $produk->harga * $qty;
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $produk->id,
                        'product_name' => $produk->nama,
                        'price' => $produk->harga,
                        'quantity' => $qty,
                        'subtotal' => $subtotal,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                    $total += $subtotal;
                }
                $order->update([
                    'total_amount' => $total,
                    'grand_total' => $total + $order->shipping_cost + $order->tax - $order->discount,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
}