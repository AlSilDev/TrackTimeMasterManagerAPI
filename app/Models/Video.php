<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'event_id',
        'video_url'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
