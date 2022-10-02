<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $adminRole = Role::factory()->create();
        $userInRole = Role::factory()->create([
            'title' => Role::USERIN_ROLE
        ]);
        $userOutRole = Role::factory()->create([
            'title' => Role::USEROUT_ROLE
        ]);

        User::factory()->create([
            'role_id' => $adminRole->id,
            'uuid' => generateUuid(),
            'firstname' => 'Richarch',
            'lastname' => 'Hendricks',
            'username' => 'rhendricks',
            'password' => bcrypt('password'),
            'deleted_at' => null,
            'deactivated_at' => null,
        ]);
        
        // Customer::factory()->create();
        // Customer::factory()->create([
        //     'customer_name' => 'Jane Doe',
        // ]);

        // Customer::factory()->create([
        //     'customer_name' => 'Samuel Austin',
        // ]);

    }
}
