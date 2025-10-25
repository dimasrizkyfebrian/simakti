<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin SIMAKTI',
            'email' => 'admin@simakti.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
