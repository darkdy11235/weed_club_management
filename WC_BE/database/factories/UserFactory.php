<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'age' => $this->faker->numberBetween(18, 60),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('123456789'),
            'salt' => '',
            'avatar' => $this->faker->imageUrl(),
            'remember_token' => '',
            'password_reset_token' => '',
            'created_by' => '',
            'status' => 'active',
        ];
    }
}


