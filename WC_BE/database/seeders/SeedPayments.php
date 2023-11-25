<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeedPayments extends Seeder
{
    public function run()
    {
        $data = [];

        for ($i = 1; $i <= 20; $i++) {
            $data[] = [
                'user_id' => rand(1, 10),
                'pay_account' => Str::random(10),
                'pay_method' => 'Credit Card',
                'account_name' => 'Account Name ' . $i,
                'account_number' => rand(10000, 999999),
                'amount_money' => rand(1, 10000),
                'description' => 'Payment description ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('payments')->insert($data);
    }
}
