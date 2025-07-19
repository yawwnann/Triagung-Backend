<?php

namespace Database\Seeders;

use App\Models\User;
// use Database\Seeders\UserSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('passwor123'),
            'role' => 'admin',
        ]);

        // Seeder tambahan
        $this->call([
            AdminUserSeeder::class,
            UserSeeder::class,
            UserProfileSeeder::class,
            AddressSeeder::class,
            KategoriProdukSeeder::class,
            OrderSeeder::class,
            WilayahSeeder::class,
        ]);
    }
}
