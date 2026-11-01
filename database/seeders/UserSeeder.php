<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private $users = [
        [
            'full_name' => 'Muhammad Miqdar Salim',
            'username' => 'miqdar',
            'password' => 'koor123',
            'role' => 'koordinator',
        ],
        [
            'full_name' => 'Fauzan Fadhila',
            'username' => 'fauzan',
            'password' => 'relawan123',
            'role' => 'relawan',
        ],
        [
            'full_name' => 'Muhamad Rafli Ar Rauf',
            'username' => 'rafli',
            'password' => 'pelapor123',
            'role' => 'pelapor',
        ],
    ];

    public function run(): void
    {
        foreach ($this->users as $user) {
            User::create([
                'full_name' => $user['full_name'],
                'username' => $user['username'],
                'password' => $user['password'],
                'role' => $user['role'],
            ]);
        }
    }
}
