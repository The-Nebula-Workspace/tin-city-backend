<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stop extends Model
{
    protected $fillable = [
        'route_id',
        'name',
        'longitude',
        'latitude',
        'order_index',
    ];

    protected function casts(): array
    {
        return [
            'longitude' => 'decimal:8',
            'latitude' => 'decimal:8',
            'order_index' => 'integer',
        ];
    }

    /**
     * Get the route that owns the stop.
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }
}
