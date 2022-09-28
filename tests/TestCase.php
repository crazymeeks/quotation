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

        $this->adminRole = Role::factory()->create([
            'title' => 'Admin'
        ]);

        $this->userInRole = Role::factory()->create([
            'title' => 'User In'
        ]);

        $this->userOutRole = Role::factory()->create([
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


    protected function authenticateAsUserOut()
    {
        User::factory()->create([
            'role_id' => $this->userOutRole->id
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
