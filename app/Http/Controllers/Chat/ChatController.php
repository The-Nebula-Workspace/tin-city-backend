<?php

namespace App\Http\Controllers\Chat;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChatRequest;
use App\Http\Resources\ChatResource;
use App\Models\Bus;
use Illuminate\Http\JsonResponse;

/**
 * @group Chat Management
 *
 * APIs for managing bus chats.
 * Base URL: `/api/v1/chats`
 */
class ChatController extends Controller
{
    /**
     * Get messages for a bus.
     *
     * @authenticated
     *
     * @urlParam bus integer required The ID of the bus. Example: 1
     *
     * @response 200 scenario="Success" {
     *   "success": true,
     *   "message": "Messages fetched successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "bus_id": 37,
     *       "user_id": 6,
     *       "message": "Is the buss full?",
     *       "created_at": "2025-11-29T14:45:42.000000Z",
     *       "updated_at": "2025-11-29T14:45:42.000000Z",
     *       "user": {
     *         "id": 6,
     *         "name": "Admin User",
     *         "email": "admin@example.com",
     *         "phone": null,
     *         "dob": null,
     *         "gender": null,
     *         "role": "admin",
     *         "points": 0,
     *         "avatar": null,
     *         "email_verified_at": null,
     *         "created_at": "2025-11-20T11:14:00.000000Z",
     *         "updated_at": "2025-11-20T11:14:00.000000Z"
     *       }
     *     }
     *   ]
     * }
     * @response 404 scenario="Not Found" {
     *   "success": false,
     *   "message": "Bus not found",
     *   "errors": null
     * }
     */
    public function index(Bus $bus): JsonResponse
    {
        try {
            $chats = $bus->chats()
                ->with('user')
                ->latest()
                ->paginate(50);

            return $this->successResponse(
                ChatResource::collection($chats),
                'Messages fetched successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve messages.', 500, $e->getMessage());
        }
    }

    /**
     * Send a message to a bus chat.
     *
     * @authenticated
     *
     * @urlParam bus integer required The ID of the bus. Example: 1
     *
     * @bodyParam message string required The message content. Example: Is the bus full?
     *
     * @response 201 scenario="Created" {
     *   "success": true,
     *   "message": "Message sent successfully",
     *   "data": {
     *     "id": 1,
     *     "bus_id": 37,
     *     "user_id": 6,
     *     "message": "Is the buss full?",
     *     "created_at": "2025-11-29T14:45:42.000000Z",
     *     "updated_at": "2025-11-29T14:45:42.000000Z",
     *     "user": {
     *       "id": 6,
     *       "name": "Admin User",
     *       "email": "admin@example.com",
     *       "phone": null,
     *       "dob": null,
     *       "gender": null,
     *       "role": "admin",
     *       "points": 0,
     *       "avatar": null,
     *       "email_verified_at": null,
     *       "created_at": "2025-11-20T11:14:00.000000Z",
     *       "updated_at": "2025-11-20T11:14:00.000000Z"
     *     }
     *   }
     * }
     * @response 422 scenario="Validation Error" {
     *   "success": false,
     *   "message": "Validation error.",
     *   "errors": {
     *     "message": ["The message field is required."]
     *   }
     * }
     * @response 404 scenario="Not Found" {
     *   "success": false,
     *   "message": "Bus not found",
     *   "errors": null
     * }
     */
    public function store(StoreChatRequest $request, Bus $bus): JsonResponse
    {
        try {
            $chat = $bus->chats()->create([
                'user_id' => $request->user()->id,
                'message' => $request->validated('message'),
            ]);

            $chat->load('user');

            broadcast(new ChatMessageSent($chat))->toOthers();

            return $this->successResponse(
                new ChatResource($chat),
                'Message sent successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to send message.', 500, $e->getMessage());
        }
    }
}
