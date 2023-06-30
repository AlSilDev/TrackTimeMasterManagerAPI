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
        Schema::create('admin_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Enrollment::class, 'enrollment_id');
            $table->boolean('verified')->nullable();
            $table->string('notes')->nullable();
            $table->foreignIdFor(User::class, 'verified_by')->nullable();;
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
