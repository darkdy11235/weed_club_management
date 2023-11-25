<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SeedUsers extends Seeder
{
    public function run()
    {
        User::factory(20)->create();
    }
}