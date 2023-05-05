<?php

use App\Models\Stage;
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
        Schema::create('stage_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Stage::class);
            $table->integer('run_num');
            $table->boolean('practice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stage_runs');
    }
};
