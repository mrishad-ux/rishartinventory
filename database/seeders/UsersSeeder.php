<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@lordofwraps.com',
                'password' => Hash::make('admin@123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@lordofwraps.com',
                'password' => Hash::make('manager@123'),
                'role' => 'manager',
            ],
            [
                'name' => 'Accounts',
                'email' => 'accounts@lordofwraps.com',
                'password' => Hash::make('accounts@123'),
                'role' => 'accounts',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}