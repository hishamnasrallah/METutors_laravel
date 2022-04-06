<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'admin',
            'email' => 'metutorsmail@gmail.com',
            'password' => Hash::make('12345678'),
            'role_name' => 'admin',
            'role_id' => '2',
        ]);
    }
}
