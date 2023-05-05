<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassificationStage extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'stage_id',
        'enrollment_id',
        'stage_points'
    ];

    protected $table = 'classifications_stage';

    public function stage(): BelongsTo {
        return $this->belongsTo(Stage::class);
    }

    public function enrollment(): BelongsTo {
        return $this->belongsTo(Enrollment::class);
    }
}
