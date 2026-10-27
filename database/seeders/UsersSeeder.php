<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@gmail.com',
                'password' => 'admin',
                'role' => 'super_admin',
                'is_active' => true,
            ],
            [
                'name' => 'Validator',
                'email' => 'validator@gmail.com',
                'password' => 'validator',
                'role' => 'validator',
                'is_active' => true,
            ],
            [
                'name' => 'Bayu',
                'email' => 'dataentry@gmail.com',
                'password' => 'dataentry',
                'role' => 'data_entry',
                'is_active' => true,
            ],
        ];

        foreach ($data as $user) {
            User::create($user);
        }

    }
}
