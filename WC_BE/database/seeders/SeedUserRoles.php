<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeedUserRoles extends Seeder
{
    public function run()
    {
        // Get all user and role IDs from their respective tables
        $userIds = DB::table('users')->pluck('id')->toArray();
        $roleIds = DB::table('roles')->pluck('id')->toArray();
        // Seed the user_role table with random user and role associations
        foreach ($userIds as $userId) {
            $randomRoleId = $roleIds[array_rand($roleIds)];

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => $randomRoleId,
            ]);
        }
    }
}
