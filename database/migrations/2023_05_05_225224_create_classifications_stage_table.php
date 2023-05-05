<?php

use App\Models\Enrollment;
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
        Schema::create('classifications_stage', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Stage::class);
            $table->foreignIdFor(Enrollment::class);
            $table->decimal('stage_points');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classifications_stage');
    }
};
