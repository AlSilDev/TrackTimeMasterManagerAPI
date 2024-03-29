<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Event categories seeder - Start");

        $categories = [['name' => 'Rally em Sprint', 'description' => 'Rally baseado no menor tempo possivel feito entre diferentes stages','created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                        ['name' => 'Rampa', 'description' => 'Rally em subidas com menor tempo entre runs','created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]];

        DB::table('event_categories')->insert($categories);

        $this->command->info("Event categories seeder - End");
    }
}
