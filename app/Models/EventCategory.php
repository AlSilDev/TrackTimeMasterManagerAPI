<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'name',
        'description'
    ];

    protected $table="event_categories";

    public function enrolled_by(): hasMany
    {
        return $this->hasMany(Event::class);
    }
}
