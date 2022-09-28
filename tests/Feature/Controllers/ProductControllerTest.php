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
        $this->authenticateAsAdmin();
    }

    /** @dataProvider dataTableRequest */
    public function testShouldGetProductDatatable(array $dtRequest)
    {
        Product::factory()->create();
        $response = $this->json('GET', route('product.datatable'), $dtRequest);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                [
                    'id',
                    'unit_of_measure_id',
                    'company_id',
                    'uuid',
                    'name',
                    'manufacturer_part_number',
                    'purchase_description',
                    'sales_description',
                    'cost',
                    'inventory',
                    'percent_discount',
                    'status',
                    'deleted_at',
                    'created_at',
                    'updated_at',
                    'measure',
                    'company_name',
                ]
            ]
        ]);
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

        $this->json('DELETE', route('product.delete'), ['id' => 1]);
        
        $product = Product::first();
        
        $this->assertNull($product);
        
    }

    public function dataTableRequest()
    {
        $dt = [
            'draw' => 1,
            'columns' => [
                [
                    'data' => 'name',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'cost',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'inventory',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'percent_discount',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'measure',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'company_name',
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