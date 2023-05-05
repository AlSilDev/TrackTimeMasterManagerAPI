<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Stage;
use App\Models\Enrollment;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('times_run', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Stage::class);
            $table->foreignIdFor(Enrollment::class);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('arrival_date');
            $table->date('departure_date');
            $table->integer('time_secs');
            $table->integer('time_mils');
            $table->boolean('started');
            $table->boolean('arrived');
            $table->integer('penalty');
            $table->foreignIdFor(User::class, 'penalty_updated_by');
            $table->string('penalty_notes');
            $table->decimal('time_points');
            $table->decimal('run_points');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('times_run');
    }
};
