<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    private $users = [
        [
            'name' => 'Admin',
            'email' => 'admin@grtech.com',
        ],
        [
            'name' => 'User',
            'email' => 'user@grtech.com',
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->users as $user) {
            User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email'],
            ]);
        }
    }
}
