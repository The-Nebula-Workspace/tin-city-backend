<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RewardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_award_points_validation_fails_if_action_missing(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/rewards', [
            'description' => 'Test',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['action']);
    }

    public function test_award_points_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/rewards', [
            'action' => 'location_share',
            'description' => 'Shared location',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'points']);
    }

    public function test_index_returns_rewards(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/rewards');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    public function test_history_returns_history(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/rewards/history');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }
}
