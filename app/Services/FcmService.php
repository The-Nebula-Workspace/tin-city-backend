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
        $client = new Client();
        $credentialsJson = base64_decode(config('services.fcm.credentials'));
        $credentials = json_decode($credentialsJson, true);
        $client->setAuthConfig($credentials);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $token = $client->fetchAccessTokenWithAssertion();

        return $token['access_token'];
    }

    public function sendNotification($deviceToken, $title, $body, $data = [])
    {
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

        $response = Http::withToken($this->accessToken)
            ->post($url, $message);

        Log::info('FCM Response: ' . $response->body());

        return $response->json();
    }
}
