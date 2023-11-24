<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeedRefunds extends Seeder
{
    public function run()
    {
        // Assuming you have 20 refunds, you can adjust the loop based on your requirement
        for ($i = 1; $i <= 20; $i++) {
            DB::table('refunds')->insert([
                'payment_id' => rand(1, 20), // Assuming you have 20 payments, adjust the range based on your requirements
                'amount' => rand(1, 100), // Adjust the range based on your requirements
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
