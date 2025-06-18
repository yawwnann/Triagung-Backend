<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::all() as $user) {
            Address::create([
                'user_id' => $user->id,
                'label' => 'Rumah',
                'recipient_name' => $user->name,
                'phone' => '08' . rand(1000000000, 9999999999),
                'address' => 'Jl. Contoh Alamat No. ' . rand(1, 100),
                'province' => 'Jawa Timur',
                'city' => 'Surabaya',
                'district' => 'Tegalsari',
                'postal_code' => '60100',
                'is_default' => true,
                'notes' => 'Alamat utama',
            ]);
        }
    }
}