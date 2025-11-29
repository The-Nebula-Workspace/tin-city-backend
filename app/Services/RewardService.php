<?php

namespace App\Services;

use App\Models\Reward;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RewardService
{
    /**
     * Add reward points for a user action
     */
    public function addPoints(User $user, string $action, ?string $description = null, bool $preventDuplicate = false)
    {
        $points = config("rewards.actions.$action", 0);

        if ($points <= 0) {
            return false;
        }

        if ($preventDuplicate) {
            $recentReward = Reward::where('user_id', $user->id)
                ->where('description', $description ?? ucfirst(str_replace('_', ' ', $action)))
                ->where('created_at', '>=', now()->subDay())
                ->exists();

            if ($recentReward) {
                return false;
            }
        }

        DB::transaction(function () use ($user, $action, $points, $description) {
            Reward::create([
                'user_id' => $user->id,
                'points' => $points,
                'description' => $description ?? ucfirst(str_replace('_', ' ', $action)),
            ]);

            $user->increment('points', $points);
        });

        return true;
    }

    /**
     * Get total reward balance
     */
    public function getUserRewards(User $user)
    {
        return $user->points ?? 0;
    }

    /**
     * Get user's reward history
     */
    public function getUserHistory(User $user)
    {
        return Reward::where('user_id', $user->id)
            ->latest()
            ->get(['points', 'description', 'created_at']);
    }
}
