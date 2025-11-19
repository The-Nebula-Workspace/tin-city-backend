<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
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
            'start_point' => $this->start_point,
            'end_point' => $this->end_point,
            'encoded_polyline' => $this->encoded_polyline,
            'distance_km' => $this->distance_km,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'stops' => $this->whenLoaded('stops', function () {
                return StopResource::collection($this->stops);
            }),
            'buses' => $this->whenLoaded('buses', function () {
                return BusResource::collection($this->buses);
            }),
            'contributions' => $this->whenLoaded('contributions', function () {
                return ContributionResource::collection($this->contributions);
            }),
        ];
    }
}
