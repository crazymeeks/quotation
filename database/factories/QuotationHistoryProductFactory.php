<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuotationHistoryProduct>
 */
class QuotationHistoryProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'quotation_history_id' => 1,
            'uuid' => generateUuid(),
            'version' => 1,
            'product_uuid' => generateUuid(),
            'company' => 'Company A',
            'product_name' => 'Product A',
            'unit_of_measure' => 'pcs',
            'manufacturer_part_number' => null,
            'purchase_description' => null,
            'sales_description' => null,
            'price' => 100,
            'quantity' => 1,
        ];
    }
}
