<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;
use App\Models\Permission;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        $permissionIds = Permission::pluck('id')->toArray();

        return [
            'permission_id' => $this->faker->randomElement($permissionIds),
        ];
    }
}
