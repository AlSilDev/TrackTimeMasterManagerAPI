<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'event_id',
        'name',
        'date_start',
    ];
}
