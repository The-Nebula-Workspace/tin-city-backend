<?php

namespace Tests\Feature;

use App\Events\ChatMessageSent;
use App\Models\Bus;
use App\Models\Chat;
use App\Models\Route;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_fetch_chat_messages()
    {
        $user = User::factory()->create();
        $route = Route::create([
            'name' => 'Test Route',
            'start_point' => 'A',
            'end_point' => 'B',
            'encoded_polyline' => 'abc',
            'distance_km' => 10,
        ]);
        $bus = Bus::create(['route_id' => $route->id, 'name' => 'Bus 1']);

        Chat::create(['bus_id' => $bus->id, 'user_id' => $user->id, 'message' => 'First Message']);
        Chat::create(['bus_id' => $bus->id, 'user_id' => $user->id, 'message' => 'Second Message']);

        $response = $this->actingAs($user)
            ->getJson("/api/v1/chat/{$bus->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Messages fetched successfully',
            ])
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['message' => 'First Message'])
            ->assertJsonFragment(['message' => 'Second Message']);
    }

    public function test_users_can_send_chat_messages()
    {
        Event::fake();

        $user = User::factory()->create();
        $route = Route::create([
            'name' => 'Test Route',
            'start_point' => 'A',
            'end_point' => 'B',
            'encoded_polyline' => 'abc',
            'distance_km' => 10,
        ]);
        $bus = Bus::create(['route_id' => $route->id, 'name' => 'Bus 1']);

        $response = $this->actingAs($user)
            ->postJson("/api/v1/chat/{$bus->id}", [
                'message' => 'Hello Bus!',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => [
                    'message' => 'Hello Bus!',
                    'bus_id' => $bus->id,
                    'user_id' => $user->id,
                ],
            ]);

        $this->assertDatabaseHas('chats', [
            'bus_id' => $bus->id,
            'user_id' => $user->id,
            'message' => 'Hello Bus!',
        ]);

        Event::assertDispatched(ChatMessageSent::class, function ($event) use ($bus) {
            return $event->chat->bus_id === $bus->id && $event->chat->message === 'Hello Bus!';
        });
    }

    public function test_chat_requires_authentication()
    {
        $route = Route::create([
            'name' => 'Test Route',
            'start_point' => 'A',
            'end_point' => 'B',
            'encoded_polyline' => 'abc',
            'distance_km' => 10,
        ]);
        $bus = Bus::create(['route_id' => $route->id, 'name' => 'Bus 1']);

        $this->getJson("/api/v1/chat/{$bus->id}")
            ->assertStatus(401);

        $this->postJson("/api/v1/chat/{$bus->id}", ['message' => 'test'])
            ->assertStatus(401);
    }

    public function test_chat_validation()
    {
        $user = User::factory()->create();
        $route = Route::create([
            'name' => 'Test Route',
            'start_point' => 'A',
            'end_point' => 'B',
            'encoded_polyline' => 'abc',
            'distance_km' => 10,
        ]);
        $bus = Bus::create(['route_id' => $route->id, 'name' => 'Bus 1']);

        $this->actingAs($user)
            ->postJson("/api/v1/chat/{$bus->id}", ['message' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }
}
