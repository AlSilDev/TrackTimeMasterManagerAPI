<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Vehicle;
use App\Models\VehicleClass;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicle_history', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('model');
            $table->foreignIdFor(VehicleClass::class, "class_id");
            $table->string('license_plate');
            $table->year('year');
            $table->integer('engine_capacity');
            $table->foreignIdFor(Vehicle::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
