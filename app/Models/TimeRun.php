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
        'participant_id',
        'arrival_date',
        'departure_date',
        'start_date',
        'end_date',
        'time_mils',
        'time_secs',
        'started',
        'arrived',
        'penalty',
        'penalty_updated_by',
        'penalty_notes',
        'time_points',
        'run_points'
    ];

    protected $table="times_run";

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function stage_run(): BelongsTo
    {
        return $this->belongsTo(StageRun::class);
    }

    public function penalty_updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
