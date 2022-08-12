<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $def = array_merge(createUUIDAttribute(), [
            
            'name' => 'Admin',
            'system_name' => 'admin'
        ]);
        
        return $def;
    }
}
