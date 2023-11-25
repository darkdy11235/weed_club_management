<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Refund;
use App\Models\Payment;

class RefundFactory extends Factory
{
    protected $model = Refund::class;

    public function definition()
    {
        $paymentIds = Payment::pluck('id')->toArray();

        return [
            'payment_id' => $this->faker->randomElement($paymentIds),
            'amount' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
