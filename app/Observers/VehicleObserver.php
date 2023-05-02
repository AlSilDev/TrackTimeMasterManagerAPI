<?php

namespace App\Observers;

use App\Models\Vehicle;
use App\Models\VehicleHistory;

class VehicleObserver
{
    /**
     * Handle the Vehicle "created" event.
     */
    public function created(Vehicle $vehicle): void
    {
        //
    }

    /**
     * Handle the Vehicle "updating" event.
     */
    public function updating(Vehicle $vehicle): void
    {
        if($vehicle->isDirty()){
            $vehicle_history = new VehicleHistory;
            $vehicle_history->vehicle_id = $vehicle->id;
            $vehicle_history->model = $vehicle->getOriginal('model');
            $vehicle_history->category = $vehicle->getOriginal('category');
            $vehicle_history->class = $vehicle->getOriginal('class');
            $vehicle_history->license_plate = $vehicle->getOriginal('license_plate');
            $vehicle_history->year = $vehicle->getOriginal('year');
            $vehicle_history->engine_capacity = $vehicle->getOriginal('engine_capacity');

            $vehicle_history->save();
        }
    }

    /**
     * Handle the Vehicle "updated" event.
     */
    public function updated(Vehicle $vehicle): void
    {
        //
    }

    /**
     * Handle the Vehicle "deleted" event.
     */
    public function deleted(Vehicle $vehicle): void
    {
        //
    }

    /**
     * Handle the Vehicle "restored" event.
     */
    public function restored(Vehicle $vehicle): void
    {
        //
    }

    /**
     * Handle the Vehicle "force deleted" event.
     */
    public function forceDeleted(Vehicle $vehicle): void
    {
        //
    }
}
