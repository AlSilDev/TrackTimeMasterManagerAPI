<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleCategory extends Model
{
    use HasFactory, HasTimestamps, SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function class(): HasMany
    {
        return $this->hasMany(VehicleClass::class);
    }
}
