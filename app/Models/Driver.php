<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'name',
        'email',
        'license_num',
        'license_expiry',
        'phone_num',
        'affiliate_num'
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
