<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'phone' => '0788888888',
            'password' => bcrypt('password'),
        ]);
        \App\Models\UserAddress::create([
            'province' => 'Kigali, City',
            'district' => 'Gasabo',
            'sector' => 'Remera',
            'cell' => 'Rukiri 1',
            'village' => 'Kisimenti',
            'user_id' => $user->id,
        ]);
    }
}
