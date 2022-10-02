<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\QuoteCode;
use App\Models\QuoteProduct;
use App\Models\UnitOfMeasure;
use App\Models\QuotationHistory;
use App\Models\QuotationProduct;
use App\Models\QuotationHistoryProduct;

class QuotationControllerTest extends TestCase
{


    public function setUp(): void
    {
        parent::setUp();
        UnitOfMeasure::factory()->create();
        Company::factory()->create();
        $this->authenticateAsUserIn();
    }
    
    /** @dataProvider data */
    public function testShouldCreateQuotation(array $data)
    {
        QuoteCode::factory()->create();
        $product = Product::factory()->create();
        QuoteProduct::factory()->create();

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
        QuoteCode::factory()->create();
        $customer = Customer::factory()->create();
        $data['customer_id'] = $customer->id;
        QuoteProduct::factory()->create();
        $product = Product::factory()->create();
        

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

    /** @dataProvider data */
    public function testShouldUpdateCustomerOfExistingQuote(array $data)
    {
        $customer = Customer::factory()->create([
            'customer_name' => $data['customer'],
        ]);
        
        $product = Product::factory()->create();
        $quotation = Quotation::factory()->create();
        QuotationProduct::factory()->create([
            'quotation_id' => $quotation->id,
        ]);

        $data['id'] = $quotation->id;

        $response = $this->json('POST', route('admin.quotation.post.save'), $data);
        
        $this->assertEquals("Quotation successfully saved.", $response->original['message']);

        $this->assertDatabaseHas('quotations', [
            'customer_id' => $customer->id
        ]);

        $this->assertDatabaseHas('quotation_products', [
            'product_name' => $product->name
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function testShouldAddProductQuotation()
    {

        $product = Product::factory()->create();

        $request = [
            'product' => $product->id,
            'quantity' => 1
        ];

        $response = $this->json('POST', route('admin.quotation.product.add.post'), $request);
        
        $this->assertArrayHasKey('html', $response);
    }

    /**
     * When user input discount quotation discount
     * send ajax request to backend to update
     * quotation being displayed
     */
    public function testShouldComputeDiscountAndDisplayQuotationProduct()
    {
        
        $request = [
            'discount' => 10,
        ];
        $response = $this->json('POST', route('admin.quotation.compute.discount'), $request);
        
        $this->assertTrue(str_contains($response->original['html'], '10%'));
    }

    public function testShouldDisplayQuantityEditModal()
    {
        Product::factory()->create();
        $qouteProduct = QuoteProduct::factory()->create();
        $request = [
            'id' => $qouteProduct->id,
        ];
        $response = $this->json('POST', route('admin.quotation.product.post.edit.modal'), $request);

        $this->assertTrue(str_contains($response->original['html'], 'Update'));
    }

    public function testShouldUpdateQuoteItemQuantity()
    {
        Product::factory()->create();
        $qouteProduct = QuoteProduct::factory()->create();
        $request = [
            'id' => $qouteProduct->id,
            'quantity' => 10,
        ];
        $response = $this->json('PUT', route('admin.quotation.product.update.quantity'), $request);
        
        $this->assertTrue(str_contains($response->original['html'], 'PHP 160,000.00'));
    }

    /**
     * When updating an existing quote item, we are passing
     * the uuid value from quotation_products table where
     * quotation products are saved
     */
    public function testShouldUpdateExistingQuoteItemQuantity()
    {
        $code = strtoupper(generate_string(15));
        Product::factory()->create();
        Customer::factory()->create();
        Quotation::factory()->create([
            'code' => $code
        ]);

        QuotationHistory::factory()->create([
            'code' => $code,
        ]);

        QuotationHistoryProduct::factory()->create();

        $qouteProduct = QuotationProduct::factory()->create();
        $request = [
            'id' => $qouteProduct->uuid,
            'quantity' => 10,
        ];
        $response = $this->json('PUT', route('admin.quotation.product.update.quantity'), $request);
        
        $this->assertTrue(str_contains($response->original['html'], 'PHP 1,000.00'));
        $this->assertDatabaseHas('quotation_history_products', [
            'version' => 2
        ]);
    }


    public function testShouldDeleteQuoteItem()
    {
        Product::factory()->create();
        $qouteProduct = QuoteProduct::factory()->create();
        $request = [
            'id' => $qouteProduct->id,
        ];
        $response = $this->json('DELETE', route('admin.quotation.product.delete'), $request);
        $this->assertArrayHasKey('html', $response->original);
    }

    /** @dataProvider data */
    public function testShouldConvertQuoteToOrder(array $data)
    {
        QuoteCode::factory()->create();
        Product::factory()->create();
        QuoteProduct::factory()->create();

        $this->json('POST', route('admin.quotation.post.convert.to.order'), $data);
        
        $this->assertDatabaseHas('order_products', [
            'final_price' => 16000
        ]);
        $this->assertDatabaseHas('quotations', [
            'status' => Quotation::CONVERTED
        ]);
    }

    public function testShouldConvertExistingQuoteToOrder()
    {
        Customer::factory()->create();
        QuoteCode::factory()->create();
        Product::factory()->create();
        
        $quotation = Quotation::factory()->create();
        QuotationProduct::factory()->create();

        $data = [
            'id' => $quotation->id,
        ];

        $response = $this->json('POST', route('admin.quotation.post.convert.to.order'), $data);
        
        $this->assertDatabaseHas('order_products', [
            'final_price' => 100
        ]);
        $this->assertDatabaseHas('quotations', [
            'status' => Quotation::CONVERTED
        ]);
    }

    /** @dataProvider dataTableRequest */
    public function testGetQuotations(array $dataTableRequest)
    {
        Customer::factory()->create();
        QuoteCode::factory()->create();
        Product::factory()->create();
        
        Quotation::factory()->create();
        QuotationProduct::factory()->create();
        $response = $this->json('GET', route('admin.quotation.get.datatable'), $dataTableRequest);
        
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                [
                    'id',
                    'customer_id',
                    'user_id',
                    'uuid',
                    'code',
                    'percent_discount',
                    'status',
                    'customer'
                ]
            ]
        ]);
    }

    public function testShouldDeletePendingQuotation()
    {
        $code = strtoupper(generate_string(15));

        Customer::factory()->create();
        Quotation::factory()->create([
            'code' => $code
        ]);

        QuotationProduct::factory()->create();

        QuotationHistory::factory()->create([
            'code' => $code
        ]);

        QuotationHistoryProduct::factory()->create();

        $request = [
            'code' => $code
        ];

        $this->json('DELETE', route('admin.quotation.delete'), $request);

        $quotationHistory = QuotationHistory::first();
        $this->assertNull($quotationHistory);
    }


    public function data()
    {
        $data = [
            'customer' => 'Customer A',
            'address' => 'Customer address A',
            'contact_no' => '09898987876',
            'code' => strtoupper(uniqid()),
            'discount' => 0.00,
        ];

        return [
            array($data)
        ];
    }


    public function dataTableRequest()
    {
        $dt = [
            'draw' => 1,
            'columns' => [
                
                [
                    'data' => 'code',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'customer',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'status',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                

            ],
            'order' => [
                [
                    'column' => '0',
                    'dir' => 'desc'
                ]
            ],
            'start' => '0',
            'length' => '10',
            'search' => [
                'value' => '',
                'regex' => 'false'
            ],
            '_' => '1600436890036',
        ];

        return [
            array($dt)
        ];
    }
}