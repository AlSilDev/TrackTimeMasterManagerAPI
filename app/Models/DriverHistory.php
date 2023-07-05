<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DriverHistory extends Model
{
    use HasFactory, HasTimestamps;

    protected $table="driver_history";

    protected $fillable = [
        'name',
        'email',
        'license_num',
        'license_expiry',
        'phone_num',
        'affiliate_num',
        'driver_id'
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function enrollments(): BelongsToMany
    {
        return $this->belongsToMany(Enrollment::class, 'id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Enrollment::class, 'id');
    }
}
