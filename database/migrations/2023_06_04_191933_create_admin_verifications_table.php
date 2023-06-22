<?php

use App\Models\Enrollment;
use App\Models\Event;
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
        Schema::create('admin_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Event::class, 'event_id');
            $table->foreignIdFor(Enrollment::class, 'enrollment_id');
            $table->boolean('enrollment_order');
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
        Schema::dropIfExists('admin_verifications');
    }
};
