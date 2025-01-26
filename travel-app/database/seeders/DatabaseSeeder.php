<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $passwordString = 'Password@123';
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt($passwordString),
                'role' => 'admin',
            ],
            [
                'name' => 'Passenger 1',
                'email' => 'passenger1@example.com',
                'password' => bcrypt($passwordString),
                'role' => 'passenger',
            ],
            [
                'name' => 'Passenger 2',
                'email' => 'passenger2@example.com',
                'password' => bcrypt($passwordString),
                'role' => 'passenger',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
