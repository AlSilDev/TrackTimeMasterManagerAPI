<?php

use App\Models\Driver;
use App\Models\DriverHistory;
use App\Models\Event;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleHistory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Event::class);
            $table->integer('enroll_order');
            $table->integer('run_order');
            $table->foreignIdFor(DriverHistory::class, 'first_driver_id');
            $table->foreignIdFor(DriverHistory::class, 'second_driver_id');
            $table->foreignIdFor(VehicleHistory::class, 'vehicle_id');
            $table->foreignIdFor(User::class, 'enrolled_by_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
