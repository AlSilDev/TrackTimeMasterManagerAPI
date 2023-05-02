<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, HasTimestamps, SoftDeletes;

    protected $fillable = [
        'model',
        'category',
        'class',
        'license_plate',
        'year',
        'engine_capacity'
    ];
}
