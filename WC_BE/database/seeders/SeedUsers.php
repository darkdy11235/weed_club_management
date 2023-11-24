<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Add this line for generating random strings

class SeedUsers extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            DB::table('users')->insert([
                'name' => 'Kiet beo ' . $i,
                'age' => rand(20, 40), // Generate a random age between 20 and 40
                'gender' => $i % 2 == 0 ? 'Male' : 'Female', // Alternating between Male and Female
                'phone' => '098765432' . $i,
                'address' => 'Da nang',
                'email' => 'kietbeo123' . $i . '@example.com',
                'password' => bcrypt('123456789'), // Use bcrypt for password hashing
                'salt' => '',
                'avatar' => 'https://www.google.com/url?sa=i&url=https%3A%2F%2Fen.wikipedia.org%2Fwiki%2FPeter_Griffin&psig=AOvVaw1Gfx61ssE6Op6ceWgcwxjN&ust=1700896438160000&source=images&cd=vfe&opi=89978449&ved=0CBEQjRxqFwoTCOjNo5GL3IIDFQAAAAAdAAAAABAJ',
                'remember_token' => Str::random(10), // Generate a random remember token
                'password_reset_token' => '',
                'created_by' => '',
                'status' => 'active'
            ]);
        }
    }
}