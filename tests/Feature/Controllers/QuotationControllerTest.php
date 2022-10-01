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
use App\Models\QuotationProduct;

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

        $this->json('POST', route('admin.quotation.post.convert.to.order'), $data);
        $this->assertDatabaseHas('order_products', [
            'final_price' => 100
        ]);
        $this->assertDatabaseHas('quotations', [
            'status' => Quotation::CONVERTED
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
        ];

        return [
            array($data)
        ];
    }
}