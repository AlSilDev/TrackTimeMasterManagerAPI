<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Participant;
use App\Models\StageRun;
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
            $table->foreignIdFor(StageRun::class, 'run_id');
            $table->foreignIdFor(Participant::class);
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->dateTime('arrival_date')->nullable();
            $table->dateTime('departure_date')->nullable();
            $table->integer('time_secs')->nullable()->default(0);
            $table->integer('time_mils')->nullable()->default(0);
            $table->boolean('started');
            $table->boolean('arrived');
            $table->integer('penalty');
            $table->foreignIdFor(User::class, 'penalty_updated_by')->nullable();
            $table->string('penalty_notes')->nullable();
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
