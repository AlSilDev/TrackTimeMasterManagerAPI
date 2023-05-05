<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeRun extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'run_id',
        'enrollment_id',
        'start_date',
        'end_date',
        'arrival_date',
        'departure_date',
        'time_secs',
        'time_mils',
        'started',
        'arrived',
        'penalty',
        'penalty_updated_by',
        'penalty_notes',
        'time_points',
        'run_points'
    ];

    protected $table="times_run";

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function stage_run(): BelongsTo
    {
        return $this->belongsTo(StageRun::class);
    }
}
