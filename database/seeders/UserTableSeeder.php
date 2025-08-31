<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'user',
            'first_name' => 'user',
            'last_name' => 'user',
            'email' => 'user@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('welcome@123'),
            'user_type' => User::CLIENT_USER_TYPE,
        ]);
    }
}
