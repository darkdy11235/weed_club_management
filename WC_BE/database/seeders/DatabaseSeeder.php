<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Payment;
use App\Models\Bill;
use App\Models\BillPayment;
use App\Models\Refund;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(20)->create();
        Permission::factory(5)->create();
        Role::factory(5)->create();
        UserRole::factory(20)->create();
        Payment::factory(20)->create();
        Bill::factory(20)->create();
        BillPayment::factory(20)->create();
        Refund::factory(20)->create();
        // $this->call(SeedUserRoles::class);
        // $this->call(SeedPayments::class);
        // $this->call(SeedRefunds::class);
        // $this->call(SeedBills::class);
        // $this->call(SeedBillPayments::class);
    }
}
