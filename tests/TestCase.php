<?php

namespace Tests;

use App\Models\User;
use App\Models\Role;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Permission;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var \Illuminate\Foundation\Application */
    protected $app;

    protected $readPerm;
    protected $writePerm;
    protected $adminPerm;

    protected $adminRole;
    protected $userInRole;
    protected $userOutRole;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([VerifyCsrfToken::class]);
        $this->createRolesAndPermissions();
    }

    protected function createRolesAndPermissions()
    {
        $this->readPerm = Permission::factory()->create([
            'title' => 'Read'
        ]);

        $this->writePerm = Permission::factory()->create([
            'title' => 'Write'
        ]);

        $this->adminPerm = Permission::factory()->create([
            'title' => 'Admin'
        ]);

        $this->adminRole = Role::factory()->create([
            'permission_id' => $this->adminPerm->id,
            'title' => 'Admin'
        ]);

        $this->userInRole = Role::factory()->create([
            'permission_id' => $this->writePerm->id,
            'title' => 'User In'
        ]);

        $this->userOutRole = Role::factory()->create([
            'permission_id' => $this->readPerm->id,
            'title' => 'User Out'
        ]);

        
    }

    protected function loginUser()
    {
        $role = Role::factory()->create();
        User::factory()->create([
            'role_id' => $role->id,
        ]);


        $cred = [
            'username' => 'admin',
            'password' => 'password'
        ];
        $response = $this->json('POST', route('admin.post.login'), $cred);
        
        return $response;
    }


    public function tearDown(): void
    {
        session()->flush();
        parent::tearDown();
    }

}
