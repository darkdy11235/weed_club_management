<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SeedUsers::class);
        $this->call(SeedPermissions::class);
        $this->call(SeedRoles::class);
        $this->call(SeedUserRoles::class);
        $this->call(SeedPayments::class);
        $this->call(SeedRefunds::class);
        $this->call(SeedBills::class);
        $this->call(SeedBillPayments::class);
    }
}

