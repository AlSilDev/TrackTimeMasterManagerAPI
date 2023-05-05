<?php

use App\Models\EventCategory;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date_start_enrollments');
            $table->date('date_end_enrollments');
            $table->date('date_start_event');
            $table->date('date_end_event');
            $table->year('year');
            $table->string('course_url');
            $table->foreignIdFor(EventCategory::class);
            $table->integer('base_penalty');
            $table->decimal('point_calc_reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
