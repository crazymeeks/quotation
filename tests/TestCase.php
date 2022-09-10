<?php

namespace Tests;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Middleware\VerifyCsrfToken;
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

    protected function authenticateAsAdmin()
    {
        User::factory()->create([
            'role_id' => $this->adminRole->id
        ]);

        $user = User::with(['role'])->first();
        
        session()->put('auth', $user);
    }

    protected function authenticateAsUserIn()
    {
        User::factory()->create([
            'role_id' => $this->userInRole->id
        ]);

        $user = User::with(['role'])->first();
        
        session()->put('auth', $user);
    }



    public function tearDown(): void
    {
        session()->flush();
        parent::tearDown();
    }

}
