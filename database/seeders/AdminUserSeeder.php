<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create requested admin user
        User::firstOrCreate(
            ['email' => 'jcicarthage.olm@gmail.com'],
            [
                'name' => 'JCI Carthage Admin',
                'password' => Hash::make('JCI_Carthage_2026!'),
                'role' => 'admin',
            ]
        );

        // Create initial admin user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@jcicarthage.org'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Test1234'),
                'role' => 'admin',
            ]
        );

        // Create a sample vice-president
        User::firstOrCreate(
            ['email' => 'vp@jci-carthage.org'],
            [
                'name' => 'Vice President',
                'password' => Hash::make('password'),
                'role' => 'vice-president',
            ]
        );

        // Create a sample member
        User::firstOrCreate(
            ['email' => 'member@jci-carthage.org'],
            [
                'name' => 'Test Member',
                'password' => Hash::make('password'),
                'role' => 'member',
            ]
        );
    }
}
