<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Vendor;
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
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'role' => 'Admin'
        ]);

        User::factory()->create([
            'name' => 'Vendor User',
            'email' => 'vendor@gmail.com',
            'role' => 'Vendor'
        ]);

        Vendor::factory()->create([
            'user_id' => '1',
        ]);
        Vendor::factory()->create([
            'user_id' => '2',
        ]);
    }
}
