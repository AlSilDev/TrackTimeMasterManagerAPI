<?php

namespace App\Observers;

use App\Models\Driver;
use App\Models\DriverHistory;
use App\Models\Enrollment;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DriverObserver
{
    /**
     * Handle the Driver "created" event.
     */
    public function created(Driver $driver): void
    {
        $driver_history = new DriverHistory();
        $driver_history->driver_id = $driver->id;
        $driver_history->name = $driver->name;
        $driver_history->email = $driver->email;
        $driver_history->country = $driver->country;
        $driver_history->license_num = $driver->license_num;
        $driver_history->license_expiry = $driver->license_expiry;
        $driver_history->phone_num = $driver->phone_num;
        $driver_history->affiliate_num = $driver->affiliate_num;

        $driver_history->save();
    }

    /**
     * Handle the Driver "updated" event.
     */
    public function updated(Driver $driver): void
    {
        if($driver->isDirty()){
            $old_driver_history_id = DriverHistory::where('driver_id', '=', $driver->id)->orderBy('created_at', 'desc')->get()[0]->id;
            $driver_history = new DriverHistory;
            $driver_history->driver_id = $driver->id;
            $driver_history->name = $driver->name;
            $driver_history->email = $driver->email;
            $driver_history->country = $driver->country;
            $driver_history->license_num = $driver->license_num;
            $driver_history->license_expiry = $driver->license_expiry;
            $driver_history->phone_num = $driver->phone_num;
            $driver_history->affiliate_num = $driver->affiliate_num;

            $driver_history->save();

            $new_driver_history_id = DriverHistory::where('driver_id', '=', $driver->id)->orderBy('created_at', 'desc')->get()[0]->id;

            /* Update every open enrollment */
            //$open_enrollments = Enrollment::join('events', 'enrollments.event_id', '=', 'events.id')->where('events.date_end_enrollments', '>=', Carbon::now())->where('enrollments.first_driver_id', '=', $old_driver_history_id)->orWhere('enrollments.second_driver_id', '=', $old_driver_history_id)->get();

            $ids = DB::table('enrollments AS e')->select('e.id')->join('events AS ev', 'ev.id', '=', 'e.event_id')->where('ev.date_end_enrollments', '>=', Carbon::now())->where('e.first_driver_id', '=', $old_driver_history_id)->orWhere('e.second_driver_id', '=', $old_driver_history_id)->pluck('e.id');
            //dd($ids);
            $open_enrollments = Enrollment::whereIn('id', $ids)->get();
            foreach($open_enrollments as $enrollment)
            {
                if ($enrollment->first_driver_id == $old_driver_history_id)
                {
                    $enrollment->first_driver_id = $new_driver_history_id;
                }
                elseif($enrollment->second_driver_id == $old_driver_history_id)
                {
                    $enrollment->second_driver_id = $new_driver_history_id;
                }

                $enrollment->save();
            }

            $ids_enrollments_open_events = DB::table('enrollments AS e')->select('e.id')->join('participants AS part', 'e.id', '=', 'part.enrollment_id')->join('events AS ev', 'ev.id', '=', 'e.event_id')->where('ev.date_end_event', '>=', Carbon::now())->where('part.first_driver_id', '=', $old_driver_history_id)->orWhere('part.second_driver_id', '=', $old_driver_history_id)->pluck('e.id');
            $enrollments_open_events = Participant::whereIn('enrollment_id', $ids_enrollments_open_events)->get();

            //dd($enrollments_open_events);
            foreach ($enrollments_open_events as $enrollment_open_event) {
                if ($enrollment_open_event->first_driver_id == $old_driver_history_id)
                {
                    $enrollment_open_event->first_driver_id = $new_driver_history_id;
                }
                elseif($enrollment_open_event->second_driver_id == $old_driver_history_id)
                {
                    $enrollment_open_event->second_driver_id = $new_driver_history_id;
                }

                $enrollment_open_event->save();
            }
        }
    }

    /**
     * Handle the Driver "deleted" event.
     */
    public function deleted(Driver $driver): void
    {
        //
    }

    /**
     * Handle the Driver "restored" event.
     */
    public function restored(Driver $driver): void
    {
        //
    }

    /**
     * Handle the Driver "force deleted" event.
     */
    public function forceDeleted(Driver $driver): void
    {
        //
    }
}
