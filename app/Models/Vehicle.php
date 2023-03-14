<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'model',
        'category',
        'class',
        'license_plate',
        'year',
        'engine_capacity'
    ];
}
