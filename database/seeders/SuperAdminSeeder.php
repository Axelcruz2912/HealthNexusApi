<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'super.admin207@gmail.com',
            'password' => Hash::make('12345678##'),
            'role' => 'SuperAdmin',
        ]);
    }
}
