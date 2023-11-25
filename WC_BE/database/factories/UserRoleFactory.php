<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;

class UserRoleFactory extends Factory
{
    protected $model = UserRole::class;

    public function definition()
    {
        $userIds = User::pluck('id')->toArray();
        $roleIds = Role::pluck('id')->toArray();

        return [
            'user_id' => $this->faker->randomElement($userIds),
            'role_id' => $this->faker->randomElement($roleIds),
        ];
    }
}
