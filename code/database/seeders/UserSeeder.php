<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@linkdev.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        // Provider
        $provider = User::firstOrCreate(
            ['email' => 'provider@linkdev.com'],
            [
                'name' => 'Provider',
                'password' => Hash::make('password'),
                'timezone' => 'Asia/Riyadh',
            ]
        );
        $provider->assignRole('provider');

        // Customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@linkdev.com'],
            [
                'name' => 'Customer',
                'password' => Hash::make('password'),
            ]
        );
        $customer->assignRole('customer');
    }
}
