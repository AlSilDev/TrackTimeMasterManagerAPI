<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enrollment extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'event_id',
        'first_driver_id',
        'second_driver_id',
        'vehicle_id',
        'enrolled_by_id'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function first_driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'first_driver_id');
    }

    public function second_driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'second_driver_id');
    }

    public function vehicle(): HasOne
    {
        return $this->hasOne(Vehicle::class);
    }

    public function enrolled_by(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
