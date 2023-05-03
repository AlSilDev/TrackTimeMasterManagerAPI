<?php

use App\Models\Enrollment;
use App\Models\User;
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
        Schema::create('technical_verfications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Enrollment::class);
            $table->boolean('verified');
            $table->string('notes');
            $table->foreignIdFor(User::class, 'verified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_verfications');
    }
};
