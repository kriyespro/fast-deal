<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'System Admin',
                'email' => 'admin@demo.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
            ],
            [
                'name' => 'Test Customer',
                'email' => 'customer@demo.com',
                'password' => password_hash('customer123', PASSWORD_DEFAULT),
                'role' => 'customer',
            ]
        ];

        $userModel = new \App\Models\UserModel();

        foreach ($data as $user) {
            $userModel->insert($user);
        }
    }
}
