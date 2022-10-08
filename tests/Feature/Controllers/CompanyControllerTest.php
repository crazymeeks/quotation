<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;

class CompanyControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->authenticateAsAdmin();

    }

    /** @dataProvider data */
    public function testAdminCreateCompany(array $data)
    {
        $response = $this->json('POST', route('admin.company.post.save'), $data);
        
        $this->assertEquals('Company successfully saved!', $response->original['message']);
        $this->assertDatabaseHas('companies', [
            'name' => strtoupper($data['name'])
        ]);
    }

    /** @dataProvider data */
    public function testShouldUpdateCompany(array $data)
    {
        $company = Company::factory()->create();
        $data['name'] = 'Company B';
        $data['id'] = $company->id;
        $response = $this->json('POST', route('admin.company.post.save'), $data);
        $this->assertEquals('Company successfully saved!', $response->original['message']);
        $this->assertDatabaseHas('companies', [
            'name' => strtoupper($data['name'])
        ]);
    }

    public function testShouldDeleteCompany()
    {
        $company = Company::factory()->create();
        
        $this->json('DELETE', route('admin.company.delete'), ['uuid' => $company->uuid]);
        $company = Company::first();
        
        $this->assertNull($company);
    }
    /** @dataProvider data */
    public function testShouldReAddDeletedCompany(array $data)
    {
        Company::factory()->create([
            'deleted_at' => now()->__toString(),
            'name' => sprintf("%s.%s", $data['name'], uniqid()),
        ]);

        $this->json('POST', route('admin.company.post.save'), $data);
        
        $this->assertDatabaseHas('companies', [
            'name' => strtoupper($data['name']),
        ]);
    }

    /** @dataProvider dataTableRequest */
    public function testShouldGetCompanyDataTable(array $dtRequest)
    {
        Company::factory()->create();
        $response = $this->json('GET', route('admin.company.datatable'), $dtRequest);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                [
                    'id',
                    'uuid',
                    'name',
                    'address',
                ]
            ]
        ]);
    }


    public function testShouldCheckIfCompanyAlreadyTaken()
    {
        Company::factory()->create([
            'name' => 'Company A'
        ]);

        $request = [
            'name' => 'Company A'
        ];

        $response = $this->json('POST', route('admin.company.post.validate'), $request);

        $response->assertJsonStructure([
            'message'
        ]);

        $this->assertEquals("The name has already been taken.", $response->original['message']);
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
                    'data' => 'address',
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
            'name' => 'Company A',
            'address' => NULL,
        ];

        return [
            array($data)
        ];
    }
}