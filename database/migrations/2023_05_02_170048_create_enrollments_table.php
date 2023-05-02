<?php

use App\Models\Driver;
use App\Models\Event;
use App\Models\User;
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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Event::class);
            $table->foreignIdFor(Driver::class, 'first_driver_id');
            $table->foreignIdFor(Driver::class, 'second_driver_id');
            $table->foreignIdFor(Vehicle::class);
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
