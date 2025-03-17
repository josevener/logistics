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
            'user_id' => 1,
            'fname' => 'John',
            'mname' => 'A.',  // Optional middle name
            'lname' => 'Doe',
            'email' => 'admin@gmail.com',
            'address' => 'West',
            'contact_info' => '09099876652',
        ]);
        Vendor::factory()->create([
            'user_id' => 2,
            'fname' => 'Juan',
            'mname' => '',  // Optional middle name
            'lname' => 'Tamad',
            'email' => 'vendor@gmail.com',
            'address' => 'Taga Looban',
            'contact_info' => '09099876654',
        ]);

    }
}
