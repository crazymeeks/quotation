<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PullOutRequestProduct>
 */
class PullOutRequestProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'pull_out_request_id' => 1,
            'quantity' => 10,
            'unit' => 'pcs',
            'product_uuid' => generateUuid(),
            'product_name' => 'Product A',
            'code' => 'code',
            'purchase_description' => 'product description',
            'size' => 'small',
            'color' => 'red',
            'remarks' => 'Pull out only',
        ];
    }
}
