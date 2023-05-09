<?php

namespace Database\Seeders;

use App\Models\VehicleCategory;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Vehicles classes seeder - Start");

        $classes_DP = ['D14', 'D15', 'D16', 'E18', 'E19', 'E20', 'F24'];
        $classes_CL = ['A3', 'C09', 'C10', 'C11', 'C12'];

        $classes = [];

        while(count($classes_DP) > 0)
        {
            $class['name'] = array_pop($classes_DP);
            $class['category_id'] = VehicleCategory::where('name', 'DP')->first()['id'];
            $class['created_at'] = Carbon::now()->toDateTimeString();
            $class['updated_at'] = Carbon::now()->toDateTimeString();

            array_push($classes, $class);
        }

        while(count($classes_CL) > 0)
        {
            $class['name'] = array_pop($classes_CL);
            $class['category_id'] = VehicleCategory::where('name', 'CL')->first()['id'];
            $class['created_at'] = Carbon::now()->toDateTimeString();
            $class['updated_at'] = Carbon::now()->toDateTimeString();
            array_push($classes, $class);
        }

        array_push($classes, ['name' => 'PR',
                                'category_id' => VehicleCategory::where('name', 'PR')->first()['id'],
                                'created_at' => Carbon::now()->toDateTimeString(),
                                'updated_at' => Carbon::now()->toDateTimeString()]);

        DB::table('vehicle_classes')->insert($classes);

        $this->command->info("Vehicles classes seeder - End");
    }
}
