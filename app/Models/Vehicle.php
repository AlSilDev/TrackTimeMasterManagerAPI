<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, HasTimestamps, SoftDeletes;

    protected $fillable = [
        'model',
        'class_id',
        'license_plate',
        'year',
        'engine_capacity'
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(VehicleClass::class, 'class_id');
    }


    public function history(): HasMany
    {
        return $this->hasMany(VehicleHistory::class, 'id');
    }
}
