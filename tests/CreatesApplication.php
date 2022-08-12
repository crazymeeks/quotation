<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();
        
        $this->migrate();
        $this->app = $app;
        return $app;
    }


    private function migrate()
    {
        Artisan::call('migrate:refresh');
        Artisan::call('migrate');
        Hash::setRounds(4);
    }

}
