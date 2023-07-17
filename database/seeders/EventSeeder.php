<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $exampleOfNames = ['Rally VP', 'Rally HT', 'RaL DD', 'Rally DS', 'VP-S RALLY', 'RALLY RACE F','MACEIRA RALLY','SEVILHA RALLY S','Rally WS'];
    private $categoryTypes = ['Rally em Sprint', 'Rampa'];
    private $numberOfEvents = [5, 4];
    private $numberOfFixedEvents = [2, 1];
    private $numberOfSoftDeleteEvents = [1,1];

    public function run(): void
    {
        $this->command->info("Events seeder - Start");

        $faker = \Faker\Factory::create('pt_PT');

        for ($typeIdx = 0; $typeIdx < count($this->categoryTypes); $typeIdx++){
            $eventCategory = $this->categoryTypes[$typeIdx];
            $totalEventsOfCategory = $this->numberOfEvents[$typeIdx];
            $totalFixedEventsOfCategory = $this->numberOfFixedEvents[$typeIdx];
            $totalSoftDeletes = $this->numberOfSoftDeleteEvents[$typeIdx];
        }

        for ($i = 1; $i <= $totalEventsOfCategory; $i++) {
            $eventNumber = $i <= $totalFixedEventsOfCategory ? $i : 0;
            $userRow = $this->newFakerEvent($faker);
            $userInfo = $this->insertEvent($faker, $userRow);
            $this->command->info("Created User '{$this->categoryTypes[$typeIdx]}' - $i / $totalEventsOfCategory");
            /*if ($eventCategory <> 'C') {
                $this->updateFoto($userInfo);
            }*/
        }
        $eventCategoryId = DB::table('user_categories')->where('sigla', $eventCategory)->pluck('id')->toArray();
        $this->softdeletes($eventCategoryId, $totalSoftDeletes);
        $this->command->info("Soft deleted $totalSoftDeletes users of type '$eventCategory'");
    }

    private function newFakerEvent($faker)
    {

        $createdAt = $faker->dateTimeBetween('-10 years', '-3 months');
        $email_verified_at = $faker->dateTimeBetween($createdAt, '-2 months');
        $updatedAt = $faker->dateTimeBetween($email_verified_at, '-1 months');

        $name = array_rand($this->exampleOfNames);
        $start_enrollments = $faker->dateTimeBetween(Carbon::now()->toDateTimeString(), '+3 days');
        $end_enrollments = $faker->dateTimeBetween('+5 days', '+7 days');
        $start_event = $faker->dateTimeBetween('+10 days', '+15 days');
        $end_event = $faker->dateTimeBetween('+20 days', '+1 months');

        $year = 2023;

        $basePenalty = rand(1000, 2000);

        $pointCalcReason = rand(0.1, 0.5);

        $categoryId = rand(1,2);

        return [
            'name' => $name,
            'date_start_enrollments' => $start_enrollments,
            'date_end_enrollments' => $end_enrollments,
            'date_start_event' => $start_event,
            'date_end_event' => $end_event,
            'year' => $year,
            'course_url' => null,
            'image_url' => null,
            'category_id' => $categoryId,
            'base_penalty' => $basePenalty,
            'point_calc_reason' => $pointCalcReason,
        ];
    }

    private function insertEvent($faker, $event)
    {
        $eventInfo = new \ArrayObject($event);
        $newId = DB::table('events')->insertGetId($event);
        $eventInfo['id'] = $newId;

        UsersSeeder::$allUsers[$newId] = $eventInfo;

        return $eventInfo;
    }

    private function softdeletes($eventTypeId, $totalSoftDeletes)
    {

        $ids = DB::table('events')->where('type_id', $eventTypeId)->pluck('id')->toArray();
        var_dump($ids);
        while ($totalSoftDeletes) {
            shuffle($ids);
            $eventInfo = UsersSeeder::$allUsers[array_shift($ids)];
            DB::update('update users set deleted_at = updated_at, blocked=1 where id = ?', [$eventInfo['id']]);
            $totalSoftDeletes--;
        }
    }
}
