<?php

namespace App\Services;

use App\Events\BadgeAwarded;
use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use App\Notifications\BadgeAchieved;

class BadgeService
{
    /**
     * Check and award badges for a user based on their points
     */
    public function checkAndAwardBadges(User $user): void
    {
        $availableBadges = Badge::where('points_required', '<=', $user->points)
            ->whereDoesntHave('userBadges', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        foreach ($availableBadges as $badge) {
            $this->awardBadge($user, $badge);
        }
    }

    /**
     * Award a badge to a user
     */
    public function awardBadge(User $user, Badge $badge): void
    {
        // Create user badge record
        UserBadge::create([
            'user_id' => $user->id,
            'badge_id' => $badge->id,
            'awarded_at' => now(),
        ]);

        // Dispatch badge awarded event
        event(new BadgeAwarded($user, $badge));

        // Send notification to user
        $user->notify(new BadgeAchieved($badge));
    }
}