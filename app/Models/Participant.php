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
        'first_driver_id',
        'second_driver_id',
        'vehicle_id'
    ];

    public function classifications_stages(): BelongsToMany
    {
        return $this->belongsToMany(ClassificationStage::class, 'id');
    }

    public function technical_verification(): HasOne
    {
        return $this->hasOne(TechnicalVerfication::class, 'id');
    }

    public function times_run(): BelongsToMany
    {
        return $this->belongsToMany(TimeRun::class, 'id');
    }

    public function enrollment(): HasOne
    {
        return $this->hasOne(Enrollment::class);
    }

    public function first_driver(): BelongsTo
    {
        return $this->belongsTo(DriverHistory::class, 'first_driver_id');
    }

    public function second_driver(): BelongsTo
    {
        return $this->belongsTo(DriverHistory::class, 'second_driver_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(VehicleHistory::class, 'vehicle_id');
    }
}
