<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@reading.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Regular Users
        User::create([
            'name' => 'ahemd ali',
            'email' => 'ahemd@reading.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'kamal asran',
            'email' => 'kamal@reading.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'mohamed ahmed',
            'email' => 'mohamed@reading.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
