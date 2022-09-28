<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Customer;

class CustomerControllerTest extends TestCase
{


    public function setUp(): void
    {
        parent::setUp();
        Customer::factory()->create();
    }

    public function testTypeAheadGetCustomerList()
    {
        $response = $this->json('GET', route('customer.typeahead.get', ['q' => 'Customer A']));
        $this->assertEquals('Customer A', $response->original['results'][0]['customer_name']);
    }
}