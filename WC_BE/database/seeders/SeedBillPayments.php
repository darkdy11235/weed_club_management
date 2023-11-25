<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeedBillPayments extends Seeder
{
    public function run()
    {
        // Assuming you have 20 bill payments, you can adjust the loop based on your requirement
        for ($i = 1; $i <= 20; $i++) {
            DB::table('bill_payments')->insert([
                'bill_id' => $i, // Assuming bill_id is the ID of the corresponding bill
                'payment_id' => rand(1, 20), // Assuming you have 20 payments, adjust the range based on your requirements
                'amount' => rand(1, 500), // Adjust the range based on your requirements
            ]);
        }
    }
}
