<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'unit_of_measure_id' => 1,
            'company_id' => 1,
            'uuid' => generateUuid(),
            'name' => 'Product A',
            'manufacturer_part_number' => null,
            'purchase_description' => 'Purchase Description A',
            'sales_description' => 'Sales Description A',
            'cost' => 16000,
            'inventory' => 100,
            'percent_discount' => 0.00,
            'status' => Product::ACTIVE,
            'deleted_at' => null,
        ];
    }
}
