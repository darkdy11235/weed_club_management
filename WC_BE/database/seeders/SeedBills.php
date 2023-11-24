<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeedBills extends Seeder
{
    public function run()
    {
        // Assuming you have 20 bills, you can adjust the loop based on your requirement
        for ($i = 1; $i <= 20; $i++) {
            DB::table('bills')->insert([
                'fee_type' => 'Type ' . $i,
                'payer' => 'Payer ' . $i,
                'fee' => rand(50, 5000), // Adjust the range based on your requirements
                'created_by' => 'User ' . $i,
                'bill_at' => now(),
                'month' => 'Month ' . $i,
                'year' => rand(2022, 2023), // Adjust the range based on your requirements
                'description' => 'Description ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
