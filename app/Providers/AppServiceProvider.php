<?php

namespace App\Providers;

use App\Models\Driver;
use App\Models\Participant;
use App\Observers\VehicleObserver;
use App\Models\Vehicle;
use App\Observers\DriverObserver;
use App\Observers\ParticipantObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vehicle::observe(VehicleObserver::class);
        Driver::observe(DriverObserver::class);
        Participant::observe(ParticipantObserver::class);
    }
}
