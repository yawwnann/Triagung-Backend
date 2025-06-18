<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Str;

class UserProfileSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::all() as $user) {
            UserProfile::create([
                'user_id' => $user->id,
                'phone' => '08' . rand(1000000000, 9999999999),
                'bio' => 'Bio untuk ' . $user->name,
                'avatar' => null,
                'gender' => ['male', 'female', 'other'][rand(0, 2)],
                'birth_date' => now()->subYears(rand(18, 40))->subDays(rand(0, 365)),
            ]);
        }
    }
}