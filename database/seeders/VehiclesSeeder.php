<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Provider\Fakecar;
use Illuminate\Support\Facades\DB;

class VehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Vehicles seeder - Start");

        $faker = \Faker\Factory::create('pt_PT');
        $faker->addProvider(new Fakecar($faker));

        $num_vehicles = 50;
        $vehicles = [];

        for($i = 0; $i < $num_vehicles; $i++) {
            $vehicle['model'] = $faker->vehicle;
            $vehicle['class'] = 'C16';
            $vehicle['category'] = 'CL';
            $vehicle['license_plate'] = $faker->regexify('[0-9]{2}-[A-Z]{2}-[0-9]{2}');
            $vehicle['year'] = $faker->year();
            $vehicle['engine_capacity'] = $faker->numberBetween(1398,2998);

            $vehicle['created_at'] = $faker->dateTimeBetween('-3 years', 'now');
            $vehicle['updated_at'] = $vehicle['created_at'];

            array_push($vehicles, $vehicle);
            $this->command->info('Created vehicle ' . $i);
        }

        DB::table('vehicles')->insert($vehicles);
        $this->command->info('Inserted vehicles in DB');
        $this->command->info("Vehicles seeder - End");
    }
}
