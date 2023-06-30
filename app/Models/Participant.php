<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Participant extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'enrollment_id',
        'can_compete',
    ];

    public function classifications_stages(): BelongsToMany
    {
        return $this->belongsToMany(ClassificationStage::class, 'id');
    }

    public function times_run(): BelongsToMany
    {
        return $this->belongsToMany(TimeRun::class, 'id');
    }

    public function enrollment(): HasOne
    {
        return $this->hasOne(Enrollment::class);
    }
}
