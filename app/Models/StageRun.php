<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StageRun extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'stage_id',
        'run_num',
        'practice',
        'ended',
        'date_start'
    ];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function times_run(): HasMany
    {
        return $this->hasMany(TimeRun::class, 'run_id');
    }
}
