<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderProduct>
 */
class OrderProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'order_id' => 1,
            'product_uuid' => generateUuid(),
            'unit_of_measure' => 1,
            'company' => 'Company A',
            'product_name' => 'Product A',
            'manufacturer_part_number' => null,
            'purchase_description' => 'Purchase description A',
            'sales_description' => 'Sales description A',
            'price' => 100,
            'percent_discount' => 0.00,
            'final_price' => 100,
        ];
    }
}
