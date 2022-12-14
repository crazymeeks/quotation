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
        $this->json('POST', route('admin.role.post.save'), $data);
        $this->assertDatabaseMissing('roles', [
            'title' => $this->userInRole->title,
        ]);
    }

    public function testShouldDeleteRole()
    {
        $data = [
            'id' => $this->userInRole->id,
        ];
        
        $response = $this->json('DELETE', route('admin.role.delete'), $data);

        $this->assertEquals('Role has been deleted.', $response->original['message']);
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