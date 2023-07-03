<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enrollment extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'event_id',
        'enroll_order',
        'run_order',
        'first_driver_id',
        'second_driver_id',
        'vehicle_id',
        'enrolled_by_id'
    ];

    public function participant(): HasOne
    {
        return $this->hasOne(Participant::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function first_driver(): BelongsTo
    {
        return $this->belongsTo(DriverHistory::class, 'first_driver_id');
    }

    public function second_driver(): BelongsTo
    {
        return $this->belongsTo(DriverHistory::class, 'second_driver_id');
    }

    //Vehicle History model because last version is updated on history too
    public function vehicle(): HasOne
    {
        return $this->hasOne(VehicleHistory::class);
    }

    public function adminVerification(): HasOne
    {
        return $this->hasOne(AdminVerification::class);
    }

    public function technicalVerifications(): HasOne
    {
        return $this->hasOne(TechnicalVerification::class);
    }

    public function enrolled_by(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
