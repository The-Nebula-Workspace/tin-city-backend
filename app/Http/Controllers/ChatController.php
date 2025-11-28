<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Http\Requests\StoreChatRequest;
use App\Http\Resources\ChatResource;
use App\Models\Bus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Chat Management
 *
 * APIs for managing bus chats.
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
     *   "data": [
     *     {
     *       "id": 1,
     *       "bus_id": 1,
     *       "user_id": 1,
     *       "message": "Hello everyone!",
     *       "created_at": "2025-11-28T12:00:00Z",
     *       "updated_at": "2025-11-28T12:00:00Z",
     *       "user": {
     *         "id": 1,
     *         "name": "John Doe",
     *         "avatar": "https://example.com/avatar.jpg"
     *       }
     *     }
     *   ]
     * }
     */
    public function index(Bus $bus): JsonResponse
    {
        $chats = $bus->chats()
            ->with('user')
            ->latest()
            ->paginate(50);

        return response()->json([
            'success' => true,
            'message' => 'Messages fetched successfully',
            'data' => ChatResource::collection($chats),
        ]);
    }

    /**
     * Send a message to a bus chat.
     *
     * @authenticated
     *
     * @urlParam bus integer required The ID of the bus. Example: 1
     * @bodyParam message string required The message content. Example: Is the bus full?
     *
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": 2,
     *     "bus_id": 1,
     *     "user_id": 1,
     *     "message": "Is the bus full?",
     *     "created_at": "2025-11-28T12:05:00Z",
     *     "updated_at": "2025-11-28T12:05:00Z",
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe"
     *     }
     *   }
     * }
     * @response 422 scenario="Validation Error" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "message": ["The message field is required."]
     *   }
     * }
     */
    public function store(StoreChatRequest $request, Bus $bus): JsonResponse
    {
        $chat = $bus->chats()->create([
            'user_id' => $request->user()->id,
            'message' => $request->validated('message'),
        ]);

        $chat->load('user');

        broadcast(new ChatMessageSent($chat))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => new ChatResource($chat),
        ], 201);
    }
}
