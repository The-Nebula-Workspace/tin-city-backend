<?php

namespace App\Services;

use Google\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    protected $projectId;
    protected $accessToken;

    public function __construct()
    {
        $this->projectId = config('services.fcm.project_id');
        $this->accessToken = $this->getAccessToken();
    }

    private function getAccessToken()
    {
        $credentialsBase64 = config('services.fcm.credentials');

        if (empty($credentialsBase64)) {
            Log::warning('FCM credentials not configured');
            return null;
        }

        try {
            $client = new Client();
            $credentialsJson = base64_decode($credentialsBase64);
            $credentials = json_decode($credentialsJson, true);

            if (!$credentials) {
                Log::warning('Invalid FCM credentials format');
                return null;
            }

            $client->setAuthConfig($credentials);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $token = $client->fetchAccessTokenWithAssertion();

            return $token['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error('Failed to get FCM access token: ' . $e->getMessage());
            return null;
        }
    }

    public function sendNotification($deviceToken, $title, $body, $data = [])
    {
        if (!$this->accessToken) {
            Log::warning('Cannot send FCM notification: Access token not available');
            return ['error' => 'FCM not configured'];
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $message = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $data,
            ],
        ];

        try {
            $response = Http::withToken($this->accessToken)
                ->post($url, $message);

            Log::info('FCM Response: ' . $response->body());

            return $response->json();
        } catch (\Exception $e) {
            Log::error('FCM notification failed: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
