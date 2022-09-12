<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Role;


class RoleControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticateAsAdmin();
        
    }

    /** @dataProvider data */
    public function testCreateRole(array $data)
    {
        $data['permission'] = $this->writePerm->id;
        
        $response = $this->json('POST', route('admin.role.post.save'), $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', [
            'title' => $data['title']
        ]);
    }

    /** @dataProvider data */
    public function testShouldUpdateRole(array $data)
    {
        $data['id'] = $this->userInRole->id;
        $data['permission'] = $this->readPerm->id;
        $this->json('POST', route('admin.role.post.save'), $data);
        $this->assertDatabaseMissing('roles', [
            'title' => $this->userInRole->title,
        ]);
    }

    public function data()
    {
        $data = [
            'title' => 'Inventory Manager'
        ];

        return [
            array($data)
        ];
    }
}