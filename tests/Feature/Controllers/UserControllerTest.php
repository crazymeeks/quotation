<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;


class UserControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticateAsAdmin();
        
    }

    /** @dataProvider dataTableRequest */
    public function testShouldGetUserList(array $dt)
    {
        User::factory()->create([
            'role_id' => 2,
            'firstname' => 'User 2',
            'lastname' => 'User 2',
            'username' => 'user2',
            'name' => 'User 2 User 2',
            'password' => bcrypt('password'),
            'status' => User::ACTIVE,
        ]);
        $response = $this->json('GET', route('admin.users.get.datatable'), $dt);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                [
                    'id',
                    'role_id',
                    'uuid',
                    'firstname',
                    'lastname',
                    'name',
                    'username',
                    'role',
                    'status',
                ]
            ]
        ]);
    }


    /** @dataProvider data */
    public function testAdminShouldCreateUser(array $data)
    {
        $data['password'] = bcrypt('password');
        $response = $this->json('POST', route('admin.users.post.save'), $data);

        $this->assertEquals('User successfully saved.', $response->original['message']);
    }

    /** @dataProvider data */
    public function testAdminShouldUpdateUser(array $data)
    {

        $user = User::factory()->create([
            'role_id' => 2,
        ]);

        $data['id'] = $user->id;
        $data['role'] = 3;
        $data['password'] = bcrypt('password');
        $response = $this->json('POST', route('admin.users.post.save'), $data);

        $this->assertEquals('User successfully saved.', $response->original['message']);
    }

    public function testAdminShouldDeleteUser()
    {
        $user = User::factory()->create([
            'role_id' => 2,
        ]);

        $this->json('DELETE', route('admin.users.delete'), ['uuid' => $user->uuid]);

        $user = User::find($user->id);
        $this->assertNull($user);
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
                    'data' => 'username',
                    'name' => NULL,
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => NULL,
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'role',
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
            'role' => 1,
            'firstname' => 'User 1',
            'lastname' => 'User 1',
            'username' => 'user',
            'password' => '',
            'status' => User::ACTIVE,
            'require_password_change' => '0',
        ];

        return [
            array($data)
        ];
    }

}