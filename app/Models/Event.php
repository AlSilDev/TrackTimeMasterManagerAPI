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
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'name',
        'date_start_enrollments',
        'date_end_enrollments',
        'date_start_event',
        'date_end_event',
        'year',
        'image_url',
        'course_url',
        'category_id',
        'base_penalty',
        'point_calc_reason'
    ];

    public function admin_verifications(): HasMany
    {
        return $this->hasMany(AdminVerification::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }
}
