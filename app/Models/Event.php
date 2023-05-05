<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory, HasTimestamps, SoftDeletes;

    protected $fillable = [
        'name',
        'date_start_enrollments',
        'date_end_enrollments',
        'date_start_event',
        'date_end_event',
        'year',
        'course_url',
        'category',
        'base_penalty'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function press(): HasMany
    {
        return $this->hasMany(Press::class);
    }

    public function regulations(): HasMany
    {
        return $this->hasMany(Regulation::class);
    }
}
