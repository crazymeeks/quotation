<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;

class LoginControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $role = Role::factory()->create();
        User::factory()->create([
            'role_id' => $role->id,
        ]);
    }

    public function testShouldLogin()
    {
        $response = $this->loginUser();

        $response->assertSessionHas('admin');
    }
}