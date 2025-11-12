<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Badge;
use App\Services\BadgeService;
use App\Events\BadgeAwarded;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BadgeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_badge_when_points_requirement_met(): void
    {
        // Arrange
        Event::fake(); // Fake events to test they are dispatched
        $user = User::factory()->create(['points' => 0]);
        $badge = Badge::create([
            'name' => 'Bronze Contributor',
            'description' => 'Earned 100 points',
            'points_required' => 100,
            'icon' => 'bronze'
        ]);

        // Act
        $user->addPoints(150); // This should trigger badge check

        // Assert
        $this->assertTrue($user->hasBadge($badge));
        Event::assertDispatched(BadgeAwarded::class);
    }

    public function test_user_does_not_receive_badge_when_points_insufficient(): void
    {
        // Arrange
        $user = User::factory()->create(['points' => 0]);
        $badge = Badge::create([
            'name' => 'Silver Contributor',
            'description' => 'Earned 200 points',
            'points_required' => 200,
            'icon' => 'silver'
        ]);

        // Act
        $user->addPoints(150); // Not enough points for the badge

        // Assert
        $this->assertFalse($user->hasBadge($badge));
    }

    public function test_user_does_not_receive_duplicate_badges(): void
    {
        // Arrange
        Event::fake();
        $user = User::factory()->create(['points' => 0]);
        $badge = Badge::create([
            'name' => 'Bronze Contributor',
            'description' => 'Earned 100 points',
            'points_required' => 100,
            'icon' => 'bronze'
        ]);

        // Act
        $user->addPoints(100); // First time
        Event::assertDispatched(BadgeAwarded::class, 1);
        
        $user->addPoints(50); // Second time, should not award again
        
        // Assert
        $this->assertEquals(1, $user->badges()->count());
        Event::assertDispatchedTimes(BadgeAwarded::class, 1);
    }

    public function test_user_can_receive_multiple_badges(): void
    {
        // Arrange
        $user = User::factory()->create(['points' => 0]);
        $bronzeBadge = Badge::create([
            'name' => 'Bronze Contributor',
            'description' => 'Earned 100 points',
            'points_required' => 100,
            'icon' => 'bronze'
        ]);
        $silverBadge = Badge::create([
            'name' => 'Silver Contributor',
            'description' => 'Earned 200 points',
            'points_required' => 200,
            'icon' => 'silver'
        ]);

        // Act
        $user->addPoints(250); // Should get both badges

        // Assert
        $this->assertTrue($user->hasBadge($bronzeBadge));
        $this->assertTrue($user->hasBadge($silverBadge));
        $this->assertEquals(2, $user->badges()->count());
    }

    public function test_badges_are_awarded_in_correct_order(): void
    {
        // Arrange
        $user = User::factory()->create(['points' => 0]);
        $badges = collect([
            Badge::create([
                'name' => 'Gold Contributor',
                'description' => 'Earned 300 points',
                'points_required' => 300,
                'icon' => 'gold'
            ]),
            Badge::create([
                'name' => 'Bronze Contributor',
                'description' => 'Earned 100 points',
                'points_required' => 100,
                'icon' => 'bronze'
            ]),
            Badge::create([
                'name' => 'Silver Contributor',
                'description' => 'Earned 200 points',
                'points_required' => 200,
                'icon' => 'silver'
            ])
        ]);

        // Act
        $user->addPoints(350); // Should get all badges

        // Assert
        $userBadges = $user->badges()->orderBy('points_required')->get();
        $this->assertEquals(3, $userBadges->count());
        $this->assertEquals('Bronze Contributor', $userBadges[0]->name);
        $this->assertEquals('Silver Contributor', $userBadges[1]->name);
        $this->assertEquals('Gold Contributor', $userBadges[2]->name);
    }
}