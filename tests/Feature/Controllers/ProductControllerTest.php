<?php

namespace Tests\Feature\Controllers;


use Tests\TestCase;

use App\Models\Company;
use App\Models\Product;
use App\Models\UnitOfMeasure;

class ProductControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        
        Company::factory()->create();
        UnitOfMeasure::factory()->create();
    }

    /**
     * @dataProvider data
     */
    public function testShouldAddProduct(array $data)
    {
        $this->authenticateAsUserIn();
        $response = $this->json('POST', route('product.save'), $data);
        
        $this->assertDatabaseHas('products', [
            'name' => $data['name']
        ]);
        
        $this->assertEquals('Product successfully created!', $response->original['message']);
    }

    /**
     * @dataProvider data
     */
    public function testUpdateProduct(array $data)
    {
        $this->authenticateAsUserIn();
        $this->json('POST', route('product.save'), $data);
        $data['id'] = 1;
        $data['name'] = 'table';
        $response = $this->json('POST', route('product.save'), $data);

        $this->assertDatabaseHas('products', [
            'name' => 'table'
        ]);

        $this->assertEquals('Product successfully updated!', $response->original['message']);
    }

    /**
     * @dataProvider data
     */
    public function testShouldDeleteProduct(array $data)
    {
        $this->authenticateAsAdmin();
        $this->json('POST', route('product.save'), $data);

        $response = $this->json('DELETE', route('product.delete'), ['id' => 1]);
        
        $product = Product::first();
        
        $this->assertNull($product);
        
    }

    public function data()
    {
        $data = [
            'unit_of_measure' => 1,
            'company' => 1,
            'name' => 'Product A',
            'manufacturer_part_number' => null,
            'purchase_description' => 'Purchase Description A',
            'sales_description' => 'Sales Description A',
            'cost' => 16000,
            'inventory' => 100,
            'percent_discount' => 0.00,
            'status' => Product::ACTIVE,
        ];

        return [
            array($data)
        ];
    }
}