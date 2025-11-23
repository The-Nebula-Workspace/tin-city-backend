<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_update_profile()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'dob' => null,
            'gender' => null,
        ]);

        $response = $this->actingAs($user)->putJson('/api/v1/user/profile', [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'phone' => '+2348012345678',
            'dob' => '1990-01-15',
            'gender' => 'male',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Profile updated successfully',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'dob',
                        'gender',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals('1990-01-15', $user->dob->format('Y-m-d'));
        $this->assertEquals('male', $user->gender);
    }

    /** @test */
    public function user_can_update_only_name()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'user@example.com',
        ]);

        $response = $this->actingAs($user)->putJson('/api/v1/user/profile', [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'user@example.com',
        ]);
    }

    /** @test */
    public function user_can_update_dob_and_gender()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/v1/user/profile', [
            'dob' => '1995-06-20',
            'gender' => 'female',
        ]);

        $response->assertStatus(200);

        $user->refresh();
        $this->assertEquals('1995-06-20', $user->dob->format('Y-m-d'));
        $this->assertEquals('female', $user->gender);
    }

    /** @test */
    public function unauthenticated_user_cannot_update_profile()
    {
        $response = $this->putJson('/api/v1/user/profile', [
            'name' => 'New Name',
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function email_must_be_unique()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $response = $this->actingAs($user1)->putJson('/api/v1/user/profile', [
            'email' => 'user2@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function user_can_keep_their_own_email()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->actingAs($user)->putJson('/api/v1/user/profile', [
            'email' => 'user@example.com',
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function dob_must_be_a_valid_date()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/v1/user/profile', [
            'dob' => 'invalid-date',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['dob']);
    }

    /** @test */
    public function dob_must_be_before_today()
    {
        $user = User::factory()->create();
        $futureDate = now()->addDay()->format('Y-m-d');

        $response = $this->actingAs($user)->putJson('/api/v1/user/profile', [
            'dob' => $futureDate,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['dob']);
    }

    /** @test */
    public function gender_must_be_valid_option()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/v1/user/profile', [
            'gender' => 'invalid-gender',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['gender']);
    }

    /** @test */
    public function gender_accepts_male_female_or_other()
    {
        $user = User::factory()->create();

        foreach (['male', 'female', 'other'] as $gender) {
            $response = $this->actingAs($user)->putJson('/api/v1/user/profile', [
                'gender' => $gender,
            ]);

            $response->assertStatus(200);

            $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'gender' => $gender,
            ]);
        }
    }

    /** @test */
    public function dob_and_gender_can_be_null()
    {
        $user = User::factory()->create([
            'dob' => '1990-01-01',
            'gender' => 'male',
        ]);

        $response = $this->actingAs($user)->putJson('/api/v1/user/profile', [
            'dob' => null,
            'gender' => null,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'dob' => null,
            'gender' => null,
        ]);
    }

    /** @test */
    public function authenticated_user_can_get_user_profile_by_id()
    {
        $authUser = User::factory()->create();
        $targetUser = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'dob' => '1990-01-15',
            'gender' => 'male',
            'points' => 150,
        ]);

        $response = $this->actingAs($authUser)->getJson("/api/v1/users/{$targetUser->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'dob',
                        'gender',
                        'role',
                        'points',
                        'avatar',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ])
            ->assertJsonPath('data.user.id', $targetUser->id)
            ->assertJsonPath('data.user.name', 'John Doe')
            ->assertJsonPath('data.user.points', 150);
    }

    /** @test */
    public function authenticated_user_can_get_their_own_profile_by_id()
    {
        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'dob' => '1995-06-20',
            'gender' => 'female',
        ]);

        $response = $this->actingAs($user)->getJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.user.id', $user->id)
            ->assertJsonPath('data.user.name', 'Jane Doe')
            ->assertJsonPath('data.user.dob', '1995-06-20')
            ->assertJsonPath('data.user.gender', 'female');
    }

    /** @test */
    public function unauthenticated_user_cannot_get_user_profile()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/v1/users/{$user->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function returns_404_for_non_existent_user()
    {
        $authUser = User::factory()->create();
        $nonExistentId = 9999;

        $response = $this->actingAs($authUser)->getJson("/api/v1/users/{$nonExistentId}");

        $response->assertStatus(404);
    }

    /** @test */
    public function user_profile_includes_all_expected_fields()
    {
        $authUser = User::factory()->create();
        $targetUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+2348012345678',
            'dob' => '1992-03-10',
            'gender' => 'other',
            'role' => 'user',
            'points' => 250,
        ]);

        $response = $this->actingAs($authUser)->getJson("/api/v1/users/{$targetUser->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.user.name', 'Test User')
            ->assertJsonPath('data.user.email', 'test@example.com')
            ->assertJsonPath('data.user.phone', '+2348012345678')
            ->assertJsonPath('data.user.dob', '1992-03-10')
            ->assertJsonPath('data.user.gender', 'other')
            ->assertJsonPath('data.user.role', 'user')
            ->assertJsonPath('data.user.points', 250);
    }
}
