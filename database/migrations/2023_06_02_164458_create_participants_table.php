<?php

use App\Models\Driver;
use App\Models\Enrollment;
use App\Models\Vehicle;
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
            $table->foreignIdFor(Driver::class, 'first_driver_id');
            $table->foreignIdFor(Driver::class, 'second_driver_id');
            $table->foreignIdFor(Vehicle::class);
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
