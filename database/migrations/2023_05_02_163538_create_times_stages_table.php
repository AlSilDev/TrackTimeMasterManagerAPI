<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Stage;
use App\Models\Enrollment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('times_stages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Stage::class);
            $table->foreignIdFor(Enrollment::class);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('time_secs');
            $table->boolean('first_run_flag');
            $table->integer('time_mils');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('times_stages');
    }
};
