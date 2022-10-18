<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Product;
use App\Models\PullOutItem;
use App\Models\UnitOfMeasure;
use App\Models\PullOutRequest;
use App\Models\PullOutRequestProduct;


class PullOutRequestControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        UnitOfMeasure::factory()->create();
        
        $this->authenticateAsAdmin();
    }


    /** @dataProvider data */
    public function testShouldPullOutProduct(array $data)
    {
        $product = Product::factory()->create();
        PullOutItem::factory()->create();
        $response = $this->json('POST', route('admin.pullout.post.save'), $data);
        
        $this->assertEquals('Product pull out request successfully saved.', $response->original['message']);

        $this->assertDatabaseHas('pull_out_request_products', [
            'product_uuid' => $product->uuid,
            'unit' => $product->unit_of_measure->title,
            'product_name' => $product->name,
        ]);
    }

    public function testShouldAddPullOutItems()
    {
        $product = Product::factory()->create();
        $request = [
            'product_id' => $product->id,
            'quantity' => 10,
        ];
        $response = $this->json('POST', route('admin.pullout.post.add.item'), $request);
        $this->assertTrue(str_contains($response->original['html'], 'Product A'));
    }

    public function testDeletePullOutItem()
    {
        Product::factory()->create();
        $item = PullOutItem::factory()->create();

        $response = $this->json('DELETE', route('admin.pullout.item.delete'), ['id' => $item->id]);
        $this->assertArrayHasKey('html', $response->original);
    }

    /** @dataProvider dataTableRequest */
    public function testShouldGetPullOutRequests(array $dataTableRequest)
    {
        PullOutRequest::factory()->create();
        PullOutRequestProduct::factory()->create();

        $response = $this->json('GET', route('admin.pullout.get.datatable'), $dataTableRequest);

        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                [
                    'id',
                    'type',
                    'por_no',
                    'business_name',
                    'address',
                    'contact_person',
                    'phone',
                    'salesman',
                    'requested_by',
                    'approved_by',
                    'returned_by',
                    'counter_checked_by',
                ]
            ],
        ]);
    }

    public function dataTableRequest()
    {
        $dt = [
            'draw' => 1,
            'columns' => [
                
                [
                    'data' => 'por_no',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'type',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'business_name',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'contact_person',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'requested_by',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],

                [
                    'data' => 'approved_by',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],

                [
                    'data' => 'returned_by',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],

                [
                    'data' => 'counter_checked_by',
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
            'type' => PullOutRequest::DEMO_ITEMS,
            'business_name' => 'Business A',
            'address' => 'Address A',
            'contact_person' => 'Contact A',
            'phone' => '0390349930',
            'salesman' => 'Salesman A',
            'requested_by' => 'John Doe',
            'approved_by' => 'John Doe',
            'returned_by' => 'John Doe',
            'counter_checked_by' => 'John Doe',
            'items' => [
                [
                    'quantity' => 1,
                    'product_id' => 1,
                    'remarks' => 'Pull out only'
                ]
            ]
        ];

        return [
            array($data)
        ];
    }
}