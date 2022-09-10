<?php

namespace Tests\Feature\Controllers;


use Tests\TestCase;

use App\Models\Product;

class ProductControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loginUser();
    }

    /**
     * @dataProvider data
     */
    public function testShouldAddProduct(array $data)
    {
        $response = $this->json('POST', route('product.save'), $data);

        $this->assertDatabaseHas('products', [
            'area' => $data['area'],
            'name' => $data['name']
        ]);
        
        $this->assertEquals('Product successfully created!', $response->original['message']);
    }

    /**
     * @dataProvider data
     */
    public function testUpdateProduct(array $data)
    {

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
        $this->json('POST', route('product.save'), $data);

        $this->json('POST', route('product.delete'), ['id' => 1]);

        $product = Product::first();

        $this->assertNull($product);
        
    }

    public function data()
    {
        $data = [
            'area' => '2cm',
            'name' => 'wooden chair',
            'image' => 'woodenchair.jpg',
            'short_description' => 'wooden chair',
            'description' => 'wooden chair',
            'price' => 3550.90,
            'percent_discount' => 0,
            'inventory' => 100,
        ];

        return [
            array($data)
        ];
    }
}