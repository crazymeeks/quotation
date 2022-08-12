<?php

namespace Tests;

use Mockery;
use App\Models\User;
use App\Authenticator\TwoFA;
use App\Authenticator\GoogleAuthenticatorProxy;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var \Illuminate\Foundation\Application */
    protected $app;

}
