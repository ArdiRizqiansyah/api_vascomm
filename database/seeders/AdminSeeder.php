<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = app('db')->select("SELECT * FROM roles WHERE name = 'admin'");

        $admin = [
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'role_id' => $role[0]->id,
        ];

        app('db')->table('users')->insert($admin);
    }
}
