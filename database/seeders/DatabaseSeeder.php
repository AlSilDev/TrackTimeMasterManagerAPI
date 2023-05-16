<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->command->info("-----------------------------------------------");
        $this->command->info("START of database seeder");
        $this->command->info("-----------------------------------------------");

        DB::statement("SET foreign_key_checks=0");

        DB::table('users')->delete();
        DB::table('drivers')->delete();
        DB::table('vehicles')->delete();

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE drivers AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE vehicles AUTO_INCREMENT = 0');

        DB::statement("SET foreign_key_checks=1");


        $this->call(UsersSeeder::class);
        $this->call(DriversSeeder::class);
        $this->call(VehicleCategoriesSeeder::class);
        $this->call(VehicleClassesSeeder::class);
        $this->call(VehiclesSeeder::class);
        $this->call(EventCategoriesSeeder::class);

        $this->command->info("-----------------------------------------------");
        $this->command->info("END of database seeder");
        $this->command->info("-----------------------------------------------");
    }
}
