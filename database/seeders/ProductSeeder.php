<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // fake data
        $faker = \Faker\Factory::create();

        // insert data dummy
        for ($i = 0; $i < 10; $i++) {
            app('db')->table('products')->insert([
                'name' => $faker->name,
                'price' => $faker->numberBetween(10000, 100000),
                'quantity' => $faker->numberBetween(1, 10),
            ]);
        }
    }
}
