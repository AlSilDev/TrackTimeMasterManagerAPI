<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class Event extends Model
{
    use HasFactory, HasTimestamps, SoftDeletes;

    protected $fillable = [
        'name',
        'date_start_enrollments',
        'date_end_enrollments',
        'date_start_event',
        'date_end_event',
        'year',
        'course_url',
        'category',
        'base_penalty'
    ];
}
