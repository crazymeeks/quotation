<?php

namespace Database\Factories;

use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quotation>
 */
class QuotationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'customer_id' => 1,
            'user_id' => 1,
            'uuid' => generateUuid(),
            'code' => strtoupper(generate_string(15)),
            'percent_discount' => 0,
            'status' => Quotation::PENDING,
        ];
    }
}
