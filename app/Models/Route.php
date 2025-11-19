<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    protected $fillable = [
        'name',
        'start_point',
        'end_point',
        'encoded_polyline',
        'distance_km',
    ];

    protected function casts(): array
    {
        return [
            'distance_km' => 'float',
        ];
    }

    /**
     * Get the stops for the route.
     */
    public function stops(): HasMany
    {
        return $this->hasMany(Stop::class);
    }

    /**
     * Get the contributions for the route.
     */
    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class);
    }

    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }
}
