<?php

namespace App\Observers;

use App\Models\ClassificationStage;
use App\Models\Participant;
use App\Models\TimeRun;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;

class ParticipantObserver
{
    /**
     * Handle the Participant "created" event.
     */
    public function created(Participant $participant): void
    {

    }

    /**
     * Handle the Participant "updated" event.
     */
    public function updating(Participant $participant): void
    {
        if ($participant->isDirty('can_compete') && $participant->can_compete == 1)
        {
            foreach($participant->enrollment->event->stages as $stage){
                $stage_pts = 0;
                foreach($stage->stage_runs as $run)
                {
                    /* Registar time_runs iniciais para aparecer nas páginas de partida */
                    $newRun = new TimeRun;
                    $newRun->run_id = $run->id;
                    $newRun->participant_id = $participant->id;

                    $start_date = new DateTime($run->start_date);
                    $start_date = $start_date->add(DateInterval::createFromDateString(($participant->enrollment->run_order - 1) . ' minutes'));
                    $newRun->start_date = $start_date;
                    $newRun->end_date = $start_date;

                    $newRun->time_mils = 0;
                    $newRun->time_secs = 0;

                    $newRun->started = false;
                    $newRun->arrived = false;
                    //A penalização inicial equivale a 2x a penalização base (1x penalização por falta de partida, 1x por falta de chegada)
                    $newRun->penalty = $run->stage->event->base_penalty * 2;
                    $newRun->run_points = $newRun->penalty;
                    $newRun->time_points = 0;

                    if (!$run->practice)
                        $stage_pts += $newRun->run_points;

                    $newRun->save();
                    /* ********************* */
                }

                /* Registar classifications stage iniciais */
                $newClassification = new ClassificationStage;
                $newClassification->stage_id = $stage->id;
                $newClassification->participant_id = $participant->id;
                $newClassification->stage_points = $stage_pts;
                $newClassification->save();
                /* ********************* */
            }
        }

    }

    /**
     * Handle the Participant "deleted" event.
     */
    public function deleted(Participant $participant): void
    {
        //
    }

    /**
     * Handle the Participant "restored" event.
     */
    public function restored(Participant $participant): void
    {
        //
    }

    /**
     * Handle the Participant "force deleted" event.
     */
    public function forceDeleted(Participant $participant): void
    {
        //
    }
}
