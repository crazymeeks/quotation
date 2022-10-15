<?php

namespace Database\Factories;

use App\Models\PullOutRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PullOutRequest>
 */
class PullOutRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => PullOutRequest::DEMO_ITEMS,
            'por_no' => invoice_num(1),
            'business_name' => 'Business A',
            'address' => 'Address A',
            'contact_person' => 'Contact A',
            'phone' => '039430430493',
            'salesman' => 'Salesman A',
            'requested_by' => 'Requested by A',
            'approved_by' => 'Approved by A',
            'returned_by' => 'Returned by A',
            'counter_checked_by' => 'Checked by A',
        ];
    }
}
