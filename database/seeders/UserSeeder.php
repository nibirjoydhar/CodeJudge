<?php

namespace Database\Seeders;

use App\Models\User; // Make sure to import your User model
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Import Hash facade

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default user if one doesn't exist, or just create one.
        // This user will typically have ID 1 if it's the first one created.
        User::firstOrCreate(
            ['email' => 'admin@example.com'], // Find by email
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Hash the password
                // Add any other default fields for your User model if needed
                // e.g., 'role' => 'admin', if you have a role column
            ]
        );

        // You can create more users using factories if you wish:
        // User::factory(5)->create();
    }
}