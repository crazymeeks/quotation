<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuotationProduct>
 */
class QuotationProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid' => generateUuid(),
            'quotation_id' => 1,
            'product_uuid' => generateUuid(),
            'unit_of_measure' => 'pcs',
            'company' => 1,
            'product_name' => 'Product A',
            'manufacturer_part_number' => null,
            'purchase_description' => null,
            'sales_description' => null,
            'price' => 100,
            'quantity' => 1,
        ];
    }
}
