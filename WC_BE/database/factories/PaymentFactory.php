<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        $userIds = User::pluck('id')->toArray();
        return [
            'user_id' => $this->faker->randomElement($userIds),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'status' => "paid"
        ];
    }
}