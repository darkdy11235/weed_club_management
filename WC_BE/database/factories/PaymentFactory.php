<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'pay_account' => $this->faker->optional()->creditCardNumber,
            'pay_method' => $this->faker->randomElement(['Credit Card', 'PayPal', 'Bank Transfer']),
            'account_name' => $this->faker->name,
            'account_number' => $this->faker->randomNumber(6),
            'amount_money' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->sentence,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}