<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Provider\Fakecar;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicle;
use App\Models\VehicleClass;

class VehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $categoryTypes = ['DP', 'CL', 'PR'];
    private $classDPTypes = ['D14', 'D15', 'D16', 'E18', 'E19', 'E20', 'F24'];
    private $classCLTypes = ['A3', 'C09', 'C10', 'C11', 'C12'];

    public function run(): void
    {
        $this->command->info("Vehicles seeder - Start");

        $faker = \Faker\Factory::create('pt_PT');
        $faker->addProvider(new Fakecar($faker));

        $num_vehicles = 50;
        $vehicles = [];

        for($i = 0; $i < $num_vehicles; $i++) {
            $vehicle['model'] = $faker->vehicle;
            $category = $faker->randomElement($this->categoryTypes);
            switch ($category) {
                case 'DP':
                    $class = $faker->randomElement($this->classDPTypes);
                    break;
                case 'CL':
                    $class = $faker->randomElement($this->classCLTypes);
                    break;
                case 'PR':
                    $class = 'PR';
                    break;
                default:
                    $class = 'PR';
                    break;
            };
            $vehicle['class_id'] = VehicleClass::where('name', $class)->first()['id'];

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
