<?php

use App\Models\Driver;
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
        Schema::create('driver_history', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('country');
            $table->integer('license_num')->unique()->nullable();
            $table->date('license_expiry');
            $table->string('phone_num')->unique();
            $table->integer('affiliate_num')->nullable();
            $table->foreignIdFor(Driver::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_history');
    }
};
