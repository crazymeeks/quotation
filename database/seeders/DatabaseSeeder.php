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

        $adminPerm = Permission::factory()->create([
            'uuid' => generateUuid(),
            'title' => 'Admin'
        ]);

        $readPerm = Permission::factory()->create([
            'uuid' => generateUuid(),
            'title' => 'Read'
        ]);
        $writePerm = Permission::factory()->create([
            'uuid' => generateUuid(),
            'title' => 'Write'
        ]);

        $adminRole = Role::factory()->create([
            'permission_id' => $adminPerm->id,
        ]);
        $userInRole = Role::factory()->create([
            'permission_id' => $writePerm->id,
            'title' => 'UserIn'
        ]);
        $userOutRole = Role::factory()->create([
            'permission_id' => $readPerm->id,
            'title' => 'UserOut'
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
        
        Customer::factory()->create();
        Customer::factory()->create([
            'customer_name' => 'Jane Doe',
        ]);

        Customer::factory()->create([
            'customer_name' => 'Samuel Austin',
        ]);

    }
}
