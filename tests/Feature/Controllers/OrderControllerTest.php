<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderProduct;

class OrderControllerTest extends TestCase
{

    protected $order;

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticateAsUserIn();
        Customer::factory()->create();
        $this->order = Order::factory()->create();
        OrderProduct::factory()->create();
    }

    /** @dataProvider dataTableRequest */
    public function testGetOrders(array $dataTableRequest)
    {
        $response = $this->json('GET', route('admin.orders.get.datatable'), $dataTableRequest);
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
                    'reference_no',
                    'grand_total',
                    'percent_discount',
                    'status',
                    'customer',
                ]
            ],
        ]);
    }


    public function testViewOrder()
    {
        $response = $this->json('GET', route('admin.orders.get.view', ['uuid' => $this->order->uuid]));

        $response->assertJsonStructure([
            'order' => [
                'customer' => [
                    'name',
                    'contact',
                    'address',
                ],
                'order' => [
                    'reference_no',
                    'status',
                    'discount',
                    'grand_total',
                ],
                'items' => [
                    [
                        'name',
                        'price',
                        'final_price',
                        'quantity',
                        'unit_of_measure'
                    ]
                ]
            ]
        ]);
        
    }


    public function dataTableRequest()
    {
        $dt = [
            'draw' => 1,
            'columns' => [
                
                [
                    'data' => 'reference_no',
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
                    'data' => 'grand_total',
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