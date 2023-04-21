<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

class VehicleHistory extends Model
{
    use HasFactory, HasTimestamps;

    protected $table="vehicle_history";

    protected $fillable = [
        'model',
        'category',
        'class',
        'license_plate',
        'year',
        'engine_capacity',
        'vehicle_id'
    ];
}
