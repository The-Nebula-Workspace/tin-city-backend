<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @group Profile Management
 *
 * APIs for managing user profiles
 */
class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @group Profile Management
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Profile retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "phone": "+1234567890",
     *     "role": "user",
     *     "dob": "1990-01-01",
     *     "gender": "male",
     *     "location": "New York, USA",
     *     "points": 100,
     *     "avatar": "https://bucket.s3.region.amazonaws.com/avatars/avatar.jpg",
     *     "email_verified_at": "2024-01-01T00:00:00.000000Z",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     */
    public function show(): JsonResponse
    {
        try {
            $user = auth()->user()->load(['contributions', 'rewards', 'userBadges']);
            
            return response()->json([
                "success" => true,
                "message" => "Profile retrieved successfully",
                "data" => new UserResource($user)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to retrieve profile",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the authenticated user's profile.
     *
     * @group Profile Management
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Profile updated successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe Updated",
     *     "email": "john@example.com",
     *     "phone": "+1234567890",
     *     "role": "user",
     *     "dob": "1990-01-01",
     *     "gender": "male",
     *     "location": "New York, USA",
     *     "points": 100,
     *     "avatar": "https://bucket.s3.region.amazonaws.com/avatars/new-avatar.jpg",
     *     "email_verified_at": "2024-01-01T00:00:00.000000Z",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Validation failed",
     *   "errors": {
     *     "name": ["The name field is required."],
     *     "avatar": ["The avatar must be an image."]
     *   }
     * }
     * 
     * @response 500 {
     *   "success": false,
     *   "message": "Failed to update profile",
     *   "error": "Server error message"
     * }
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $data = $request->validated();

            // Remove points from data if present (points should be managed separately)
            if (array_key_exists('points', $data)) {
                unset($data['points']);
            }

            // Handle S3 avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar) {
                    $oldAvatarPath = parse_url($user->avatar, PHP_URL_PATH);
                    if ($oldAvatarPath) {
                        Storage::disk('s3')->delete($oldAvatarPath);
                    }
                }

                // Generate unique filename
                $extension = $request->file('avatar')->getClientOriginalExtension();
                $filename = 'avatar-' . $user->id . '-' . Str::random(10) . '.' . $extension;
                
                // Store on S3
                $path = $request->file('avatar')->storeAs('avatars', $filename, 's3');
                $data['avatar'] = Storage::disk('s3')->url($path);
            }

            $user->update($data);

            return response()->json([
                "success" => true,
                "message" => "Profile updated successfully",
                "data" => new UserResource($user->fresh())
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                "success" => false,
                "message" => "Validation failed",
                "errors" => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to update profile",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}