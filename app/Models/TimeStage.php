<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

class TimeStage extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'name',
        'description'
    ];

    protected $table="times_stages";
}
