<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DriversSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Drivers seeder - Start");

        $faker = \Faker\Factory::create('pt_PT');
        $faker_en = \Faker\Factory::create('en_US');

        $num_drivers = 50;
        $drivers = [];
        $used_numbers = [];

        for($i = 0; $i < $num_drivers; $i++) {
            $driver['name'] = $faker->name;
            $driver['email'] = $faker->email;

            do {
                $driver['license_num'] = $faker->numberBetween(100,2000);
            } while (in_array($driver['license_num'], $used_numbers));
            array_push($used_numbers, $driver['license_num']);

            $driver['license_expiry'] = $faker->dateTimeBetween('now', '+2 years');
            $driver['affiliate_num'] = $faker->numberBetween(100, 999);

            $driver['phone_num'] = str_replace(' ', '', $faker->phoneNumber);

            $driver['country'] = ($r_val = rand(0,2)) == 0 ? 'prt' : strtolower($faker_en->countryISOAlpha3());

            $driver['created_at'] = $faker->dateTimeBetween('-3 years', 'now');
            $driver['updated_at'] = $driver['created_at'];

            array_push($drivers, $driver);
        }

        //DB::table('drivers')->insert($drivers);
        foreach($drivers as $driver)
        {
            Driver::create($driver);
            $this->command->info('Created driver ' . $driver['name']);
        }

        $this->command->info('Inserted drivers in DB');
        $this->command->info("Drivers seeder - End");

    }
}
