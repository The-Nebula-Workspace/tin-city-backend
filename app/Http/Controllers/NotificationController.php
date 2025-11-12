<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendNotificationRequest;
use App\Services\FcmService;
use Illuminate\Http\JsonResponse;

/**
 * @group Notifications
 *
 * APIs for managing push notifications
 */
class NotificationController extends Controller
{
    protected FcmService $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * Send a Test Notification
     *
     * This endpoint sends a push notification to a device using Firebase Cloud Messaging (FCM).
     *
     * @bodyParam token string required The FCM device token. Example: "dYV1S2A4-abc123xyz"
     * @bodyParam title string required The title of the notification. Example: "Reward Earned"
     * @bodyParam body string required The notification message body. Example: "You received 50 bonus points!"
     * @bodyParam type string optional The type of notification. Must be one of: reward, route_update, admin_alert. Example: "reward"
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Notification sent successfully!",
     *   "response": {
     *     "name": "projects/.../messages/12345"
     *   }
     * }
     * @response 422 {
     *   "success": false,
     *   "message": "Validation failed",
     *   "errors": {
     *     "token": ["The token field is required."]
     *   }
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Failed to send notification.",
     *   "error": "Server error message"
     * }
     */
    public function testNotification(SendNotificationRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $response = $this->fcmService->sendNotification(
                $validated['token'],
                $validated['title'],
                $validated['body'],
                ['type' => $validated['type'] ?? 'reward']
            );

            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully!',
                'response' => $response,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
