<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'route_id' => $this->route_id,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'order_index' => $this->order_index,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'route' => $this->whenLoaded('route', function () {
                return new RouteResource($this->route);
            }),
        ];
    }
}
