<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BillPayment;
use App\Models\Bill;
use App\Models\Payment;

class BillPaymentFactory extends Factory
{
    protected $model = BillPayment::class;

    public function definition()
    {
        $billIds = Bill::pluck('id')->toArray();
        $paymentIds = Payment::pluck('id')->toArray();

        return [
            'bill_id' => $this->faker->randomElement($billIds),
            'payment_id' => $this->faker->randomElement($paymentIds),
            'amount' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
