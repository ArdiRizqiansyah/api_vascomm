<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat user menggunakan faker
        $faker = \Faker\Factory::create('id_ID');

        // buat role user
        $role = app('db')->select("SELECT * FROM roles WHERE name = 'user'");

        // buat 10 user
        for ($i = 0; $i < 10; $i++) {
            $user = [
                'name' => $faker->name,
                'email' => $faker->email,
                'role_id' => $role[0]->id,
            ];

            app('db')->table('users')->insert($user);
        }
    }
}
