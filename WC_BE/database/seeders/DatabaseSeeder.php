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
use App\Models\RolePermission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        Permission::insert([
            ['name' => 'Create Bill Payment', 'slug' => 'create_bill_payment', 'description' => 'Permission to create a new bill payment', 'create' => true, 'read' => false, 'update' => false, 'delete' => false],
            ['name' => 'Read Bill Payment', 'slug' => 'read_bill_payment', 'description' => 'Permission to view bill payment details', 'create' => false, 'read' => true, 'update' => false, 'delete' => false],
            ['name' => 'Update Bill Payment', 'slug' => 'update_bill_payment', 'description' => 'Permission to update an existing bill payment', 'create' => false, 'read' => false, 'update' => true, 'delete' => false],
            ['name' => 'Delete Bill Payment', 'slug' => 'delete_bill_payment', 'description' => 'Permission to delete a bill payment', 'create' => false, 'read' => false, 'update' => false, 'delete' => true],

            ['name' => 'Create Bill', 'slug' => 'create_bill', 'description' => 'Permission to create a new bill', 'create' => true, 'read' => false, 'update' => false, 'delete' => false],
            ['name' => 'Read Bill', 'slug' => 'read_bill', 'description' => 'Permission to view bill details', 'create' => false, 'read' => true, 'update' => false, 'delete' => false],
            ['name' => 'Update Bill', 'slug' => 'update_bill', 'description' => 'Permission to update an existing bill', 'create' => false, 'read' => false, 'update' => true, 'delete' => false],
            ['name' => 'Delete Bill', 'slug' => 'delete_bill', 'description' => 'Permission to delete a bill', 'create' => false, 'read' => false, 'update' => false, 'delete' => true],

            ['name' => 'Run Migrations', 'slug' => 'run_migrations', 'description' => 'Permission to run database migrations', 'create' => true, 'read' => true, 'update' => false, 'delete' => false],

            ['name' => 'Create Password Reset', 'slug' => 'create_password_reset', 'description' => 'Permission to create a new password reset record', 'create' => true, 'read' => false, 'update' => false, 'delete' => false],
            ['name' => 'Read Password Reset', 'slug' => 'read_password_reset', 'description' => 'Permission to view password reset details', 'create' => false, 'read' => true, 'update' => false, 'delete' => false],
            ['name' => 'Delete Password Reset', 'slug' => 'delete_password_reset', 'description' => 'Permission to delete a password reset record', 'create' => false, 'read' => false, 'update' => false, 'delete' => true],

            ['name' => 'Create Payment', 'slug' => 'create_payment', 'description' => 'Permission to create a new payment', 'create' => true, 'read' => false, 'update' => false, 'delete' => false],
            ['name' => 'Read Payment', 'slug' => 'read_payment', 'description' => 'Permission to view payment details', 'create' => false, 'read' => true, 'update' => false, 'delete' => false],
            ['name' => 'Update Payment', 'slug' => 'update_payment', 'description' => 'Permission to update an existing payment', 'create' => false, 'read' => false, 'update' => true, 'delete' => false],
            ['name' => 'Delete Payment', 'slug' => 'delete_payment', 'description' => 'Permission to delete a payment', 'create' => false, 'read' => false, 'update' => false, 'delete' => true],

            ['name' => 'Create Refund', 'slug' => 'create_refund', 'description' => 'Permission to create a new refund', 'create' => true, 'read' => false, 'update' => false, 'delete' => false],
            ['name' => 'Read Refund', 'slug' => 'read_refund', 'description' => 'Permission to view refund details', 'create' => false, 'read' => true, 'update' => false, 'delete' => false],
            ['name' => 'Update Refund', 'slug' => 'update_refund', 'description' => 'Permission to update an existing refund', 'create' => false, 'read' => false, 'update' => true, 'delete' => false],
            ['name' => 'Delete Refund', 'slug' => 'delete_refund', 'description' => 'Permission to delete a refund', 'create' => false, 'read' => false, 'update' => false, 'delete' => true],

            ['name' => 'Create Role', 'slug' => 'create_role', 'description' => 'Permission to create a new role', 'create' => true, 'read' => false, 'update' => false, 'delete' => false],
            ['name' => 'Read Role', 'slug' => 'read_role', 'description' => 'Permission to view role details', 'create' => false, 'read' => true, 'update' => false, 'delete' => false],
            ['name' => 'Update Role', 'slug' => 'update_role', 'description' => 'Permission to update an existing role', 'create' => false, 'read' => false, 'update' => true, 'delete' => false],
            ['name' => 'Delete Role', 'slug' => 'delete_role', 'description' => 'Permission to delete a role', 'create' => false, 'read' => false, 'update' => false, 'delete' => true],

            ['name' => 'Assign Role Permissions', 'slug' => 'assign_role_permissions', 'description' => 'Permission to assign permissions to a role', 'create' => true, 'read' => true, 'update' => true, 'delete' => true],

            ['name' => 'Create User', 'slug' => 'create_user', 'description' => 'Permission to create a new user', 'create' => true, 'read' => false, 'update' => false, 'delete' => false],
            ['name' => 'Read User', 'slug' => 'read_user', 'description' => 'Permission to view user details', 'create' => false, 'read' => true, 'update' => false, 'delete' => false],
            ['name' => 'Update User', 'slug' => 'update_user', 'description' => 'Permission to update an existing user', 'create' => false, 'read' => false, 'update' => true, 'delete' => false],
            ['name' => 'Delete User', 'slug' => 'delete_user', 'description' => 'Permission to delete a user', 'create' => false, 'read' => false, 'update' => false, 'delete' => true],

            ['name' => 'Assign User Roles', 'slug' => 'assign_user_roles', 'description' => 'Permission to assign roles to a user', 'create' => true, 'read' => true, 'update' => true, 'delete' => true],

            ['name' => 'Create Permission', 'slug' => 'create_permission', 'description' => 'Permission to create a new permission', 'create' => true, 'read' => false, 'update' => false, 'delete' => false],
            ['name' => 'Read Permission', 'slug' => 'read_permission', 'description' => 'Permission to view permission details', 'create' => false, 'read' => true, 'update' => false, 'delete' => false],
            ['name' => 'Update Permission', 'slug' => 'update_permission', 'description' => 'Permission to update an existing permission', 'create' => false, 'read' => false, 'update' => true, 'delete' => false],
            ['name' => 'Delete Permission', 'slug' => 'delete_permission', 'description' => 'Permission to delete a permission', 'create' => false, 'read' => false, 'update' => false, 'delete' => true],
        ]);

        Role::insert([
            ['role_name' => 'Admin'],
            ['role_name' => 'User']
        ]);

        User::create([
            'name' => 'admin',
            'age' => '30',
            'gender' => 'Male',
            'phone' => '098765671',
            'address' => '44 thanh long',
            'email' => 'htk11235@gmail.com',
            'password' => bcrypt('admin@example.com'),
        ]);
        
        UserRole::create([
            'user_id' => 1,
            'role_id' => 1
        ]);

        User::factory(20)->create();

        //UserRole::factory(20)->create();

        Payment::factory(20)->create();

        $adminUsername = 'Administrator';
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        $startYear = 2023;
        $endYear = 2024;
        foreach (range($startYear, $endYear) as $year) {
            foreach ($months as $month) {
                Bill::create([
                    'fee_type' => 'Monthly',
                    'payer' => 'User',
                    'fee' => 10.00,
                    'bill_at' => now(),
                    'month' => $month,
                    'year' => $year,
                    'description' => "Monthly bill for $month $year",
                    'created_by' => $adminUsername,
                ]);
            }
        }

        Refund::factory(20)->create();

        RolePermission::insert([
            // Admin role permissions
            ['role_id' => 1, 'permission_id' => 1], // Create Bill Payment
            ['role_id' => 1, 'permission_id' => 2], // Read Bill Payment
            ['role_id' => 1, 'permission_id' => 3], // Update Bill Payment
            ['role_id' => 1, 'permission_id' => 4], // Delete Bill Payment

            ['role_id' => 1, 'permission_id' => 5], // Create Bill
            ['role_id' => 1, 'permission_id' => 6], // Read Bill
            ['role_id' => 1, 'permission_id' => 7], // Update Bill
            ['role_id' => 1, 'permission_id' => 8], // Delete Bill

            ['role_id' => 1, 'permission_id' => 9], // Run Migrations

            ['role_id' => 1, 'permission_id' => 10], // Create Password Reset
            ['role_id' => 1, 'permission_id' => 11], // Read Password Reset
            ['role_id' => 1, 'permission_id' => 12], // Delete Password Reset

            ['role_id' => 1, 'permission_id' => 13], // Create Payment
            ['role_id' => 1, 'permission_id' => 14], // Read Payment
            ['role_id' => 1, 'permission_id' => 15], // Update Payment
            ['role_id' => 1, 'permission_id' => 16], // Delete Payment

            ['role_id' => 1, 'permission_id' => 17], // Create Refund
            ['role_id' => 1, 'permission_id' => 18], // Read Refund
            ['role_id' => 1, 'permission_id' => 19], // Update Refund
            ['role_id' => 1, 'permission_id' => 20], // Delete Refund

            ['role_id' => 1, 'permission_id' => 21], // Create Role
            ['role_id' => 1, 'permission_id' => 22], // Read Role
            ['role_id' => 1, 'permission_id' => 23], // Update Role
            ['role_id' => 1, 'permission_id' => 24], // Delete Role

            ['role_id' => 1, 'permission_id' => 25], // Assign Role Permissions

            ['role_id' => 1, 'permission_id' => 26], // Create User
            ['role_id' => 1, 'permission_id' => 27], // Read User
            ['role_id' => 1, 'permission_id' => 28], // Update User
            ['role_id' => 1, 'permission_id' => 29], // Delete User

            ['role_id' => 1, 'permission_id' => 30], // Assign User Roles

            ['role_id' => 1, 'permission_id' => 31], // Create Permission
            ['role_id' => 1, 'permission_id' => 32], // Read Permission
            ['role_id' => 1, 'permission_id' => 33], // Update Permission
            ['role_id' => 1, 'permission_id' => 34], // Delete Permission

            // User role permissions
            ['role_id' => 2, 'permission_id' => 6], // Read Bill
            ['role_id' => 2, 'permission_id' => 27], // Read User
            ['role_id' => 2, 'permission_id' => 14], // Read Payment
            ['role_id' => 2, 'permission_id' => 28], // Update User
            ['role_id' => 2, 'permission_id' => 13], // Create Payment
        ]);
    }
}
