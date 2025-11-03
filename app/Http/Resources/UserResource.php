<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'location' => $this->location,
            'points' => $this->points,
            'avatar' => $this->avatar,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'contributions' => $this->whenLoaded('contributions', function () {
                return ContributionResource::collection($this->contributions);
            }),
            'chats' => $this->whenLoaded('chats', function () {
                return ChatResource::collection($this->chats);
            }),
            'rewards' => $this->whenLoaded('rewards', function () {
                return RewardResource::collection($this->rewards);
            }),
            'user_badges' => $this->whenLoaded('userBadges', function () {
                return UserBadgeResource::collection($this->userBadges);
            }),
        ];
    }
}