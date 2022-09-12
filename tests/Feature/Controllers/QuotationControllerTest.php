<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Company;
use App\Models\Customer;
use App\Models\UnitOfMeasure;

class QuotationControllerTest extends TestCase
{


    public function setUp(): void
    {
        parent::setUp();
        UnitOfMeasure::factory()->create();
        Company::factory()->create();
    }
    
    /** @dataProvider data */
    public function testShouldCreateQuotation(array $data)
    {
        $product = Product::factory()->create();
        $this->authenticateAsUserIn();

        $response = $this->json('POST', route('admin.quotation.post.save'), $data);
        
        $this->assertEquals("Quotation successfully saved.", $response->original['message']);
        $this->assertDatabaseHas('quotation_products', [
            'product_name' => $product->name
        ]);

        $this->assertDatabaseHas('quotation_history_products', [
            'product_name' => $product->name
        ]);
    }

    /** @dataProvider data */
    public function testShouldCreateQuotationOnExistingCustomer(array $data)
    {
        $customer = Customer::factory()->create();
        $data['customer_id'] = $customer->id;

        $product = Product::factory()->create();
        $this->authenticateAsUserIn();

        $response = $this->json('POST', route('admin.quotation.post.save'), $data);
        
        $this->assertEquals("Quotation successfully saved.", $response->original['message']);

        $this->assertDatabaseHas('quotations', [
            'customer_id' => $customer->id
        ]);

        $this->assertDatabaseHas('quotation_products', [
            'product_name' => $product->name
        ]);

        $this->assertDatabaseHas('quotation_history_products', [
            'product_name' => $product->name
        ]);

    }

    public function data()
    {
        $data = [
            'customer' => 'Customer A',
            'address' => 'Customer address A',
            'contact_no' => '09898987876',
            'code' => strtoupper(uniqid()),
            'discount' => 0.00,
            'items' => [
                [
                    'product_id' => 1,
                    'quantity' => 1
                ]
            ]
        ];

        return [
            array($data)
        ];
    }
}