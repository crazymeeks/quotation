<?php

namespace Tests;

use App\Models\User;
use App\Models\Role;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var \Illuminate\Foundation\Application */
    protected $app;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([VerifyCsrfToken::class]);
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

}
