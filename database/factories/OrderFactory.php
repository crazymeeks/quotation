<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
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
            'reference_no' => generate_string(),
            'grand_total' => 100,
            'percent_discount' => 0.00,
            'status' => Order::PENDING,
        ];
    }
}
