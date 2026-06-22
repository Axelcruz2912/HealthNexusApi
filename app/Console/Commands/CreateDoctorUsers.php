<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CreateDoctorUsers extends Command
{
    protected $signature = 'doctors:create';
    protected $description = 'Create doctor users';

    public function handle()
    {
        $users = [
            ['name' => 'Dr. Kenia Medina', 'email' => 'kenniamedina627@gmail.com', 'role' => 'Médico A'],
            ['name' => 'Dr. SF Gilkey', 'email' => 'sfgilkey@gmail.com', 'role' => 'Médico B'],
            ['name' => 'Dr. KM', 'email' => 'kmedinaa26@gmail.com', 'role' => 'Médico C'],
        ];

        foreach ($users as $u) {
            if (!User::where('email', $u['email'])->exists()) {
                User::create([
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'password' => bcrypt('12345678##'),
                    'role' => $u['role'],
                    'finance_pin' => '1234',
                    'validation_status' => 'Aprobado',
                    'email_verified_at' => now(),
                    'status' => 1,
                ]);
                $this->info('Creado: ' . $u['name']);
            } else {
                $this->info('Ya existe: ' . $u['email']);
            }
        }
    }
}
