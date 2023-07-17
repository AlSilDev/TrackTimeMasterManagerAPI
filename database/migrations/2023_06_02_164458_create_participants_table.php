<?php

use App\Models\DriverHistory;
use App\Models\Enrollment;
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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Enrollment::class);
            $table->foreignIdFor(DriverHistory::class, 'first_driver_id');
            $table->foreignIdFor(DriverHistory::class, 'second_driver_id');
            $table->foreignIdFor(VehicleHistory::class, 'vehicle_id');
            $table->boolean('can_compete')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
