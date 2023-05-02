<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeStage extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'name',
        'description'
    ];

    protected $table="times_stage";

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
}
