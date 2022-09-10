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
            'name' => $data['name']
        ]);
    }

    /** @dataProvider data */
    public function testShouldUpdateCompany(array $data)
    {
        Company::factory()->create();
        $data['name'] = 'Company B';
        $response = $this->json('POST', route('admin.company.post.save'), $data);
        $this->assertEquals('Company successfully saved!', $response->original['message']);
        $this->assertDatabaseHas('companies', [
            'name' => $data['name']
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
            'name' => $data['name'],
        ]);
    }



    public function data()
    {
        $data = [
            'name' => 'Company A',
        ];

        return [
            array($data)
        ];
    }
}