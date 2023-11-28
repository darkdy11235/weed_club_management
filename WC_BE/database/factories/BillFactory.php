<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Bill;

class BillFactory extends Factory
{
    protected $model = Bill::class;

    public function definition()
    {
        return [
            'fee_type' => $this->faker->word,
            'payer' => $this->faker->name,
            'fee' => $this->faker->numberBetween(50, 5000),
            'created_by' => $this->faker->name,
            'bill_at' => $this->faker->date,
            'month' => $this->faker->word,
            'year' => $this->faker->numberBetween(2022, 2023),
            'description' => $this->faker->sentence,

        ];
    }
}
