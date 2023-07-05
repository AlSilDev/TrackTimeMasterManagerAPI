<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleHistory extends Model
{
    use HasFactory, HasTimestamps;

    protected $table="vehicle_history";

    protected $fillable = [
        'model',
        'class_id',
        'license_plate',
        'year',
        'engine_capacity',
        'vehicle_id'
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(VehicleClass::class, 'class_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class, 'id');
    }
}
