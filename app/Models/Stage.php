<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'event_id',
        'name',
        'date_start',
        'num_runs',
        'time_until_next_run_mins'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function classifications_stage(): HasMany
    {
        return $this->hasMany(ClassificationStage::class);
    }

    public function stage_runs(): HasMany
    {
        return $this->hasMany(StageRun::class);
    }
}
