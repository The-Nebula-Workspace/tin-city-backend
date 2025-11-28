<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    protected $fillable = [
        'bus_id',
        'user_id',
        'message',
    ];

    /**
     * Get the user that owns the chat.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bus that owns the chat.
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }
}
