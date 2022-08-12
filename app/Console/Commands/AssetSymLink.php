<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class AssetSymLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sym:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create asset symlink';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        // link asset
        $assets = public_path('assets');

        if (!realpath($assets)) {
            try {
                unlink($assets);
            } catch (Exception $e) {

            }
            symlink(resource_path('assets'), $assets);
        }

        if (!file_exists($assets)) {
            symlink(resource_path('assets'), $assets);
        }
        
    }
}
