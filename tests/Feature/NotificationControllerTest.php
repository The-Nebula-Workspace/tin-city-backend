<?php

namespace Tests\Feature;

use App\Services\FcmService;
use Mockery;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    public function test_it_returns_success_response_when_notification_is_sent(): void
    {
        $payload = [
            'token' => 'device-token',
            'title' => 'Hello',
            'body' => 'Test notification',
            'type' => 'reward',
        ];

        $fcmResponse = ['name' => 'projects/test/messages/123'];

        $mock = Mockery::mock(FcmService::class);
        $mock->shouldReceive('sendNotification')
            ->once()
            ->with(
                $payload['token'],
                $payload['title'],
                $payload['body'],
                ['type' => $payload['type']]
            )
            ->andReturn($fcmResponse);

        $this->instance(FcmService::class, $mock);

        $response = $this->postJson('/api/v1/notifications/test', $payload);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Notification sent successfully!',
                'response' => $fcmResponse,
            ]);
    }

    public function test_it_returns_error_response_when_fcm_reports_error(): void
    {
        $payload = [
            'token' => 'device-token',
            'title' => 'Hello',
            'body' => 'Test notification',
        ];

        $fcmResponse = [
            'error' => [
                'code' => 401,
                'message' => 'Authentication required.',
                'status' => 'UNAUTHENTICATED',
            ],
        ];

        $mock = Mockery::mock(FcmService::class);
        $mock->shouldReceive('sendNotification')
            ->once()
            ->andReturn($fcmResponse);

        $this->instance(FcmService::class, $mock);

        $response = $this->postJson('/api/v1/notifications/test', $payload);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Authentication required.',
                'error' => $fcmResponse['error'],
            ]);
    }
}
