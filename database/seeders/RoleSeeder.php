<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin', 'user',
        ];

        foreach ($roles as $role) {
            app('db')->table('roles')->insert([
                'name' => $role,
            ]);
        }
    }
}
