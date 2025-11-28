<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BusCrowdingUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $routeId;

    public string $status;

    public int $crowdingLevel;

    public array $data;

    /**
     * Create a new event instance.
     */
    public function __construct(int $routeId, string $status, int $crowdingLevel, array $data)
    {
        $this->routeId = $routeId;
        $this->status = $status;
        $this->crowdingLevel = $crowdingLevel;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('route.'.$this->routeId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'bus.crowding.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'route_id' => $this->routeId,
            'status' => $this->status,
            'crowding_level' => $this->crowdingLevel,
            'location' => [
                'latitude' => $this->data['latitude'],
                'longitude' => $this->data['longitude'],
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
