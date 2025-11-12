<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Badge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BadgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_badge(): void
    {
        // Create admin user
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/badges', [
            'name' => 'Super Contributor',
            'description' => 'Earned by contributing 1000 points',
            'points_required' => 1000,
            'icon' => 'trophy'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'Super Contributor',
                'points_required' => 1000
            ]);
    }

    public function test_non_admin_cannot_create_badge(): void
    {
        // Create regular user
        $user = User::factory()->create(['role' => 'user']);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/badges', [
            'name' => 'Test Badge',
            'description' => 'Test Description',
            'points_required' => 100
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_see_earned_badges(): void
    {
        // Create user and badge
        $user = User::factory()->create(['points' => 1500]);
        $badge = Badge::create([
            'name' => 'Super Contributor',
            'description' => 'Earned by contributing 1000 points',
            'points_required' => 1000,
            'icon' => 'trophy'
        ]);

        // Trigger badge check
        $user->addPoints(0); // This will check for badges
        
        $token = $user->createToken('test-token')->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->getJson('/api/user/badges');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'name' => 'Super Contributor',
                'points_required' => 1000
            ]);
    }
}