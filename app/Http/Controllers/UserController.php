<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * @group User Management
 *
 * Endpoints for managing user profile information.
 */
class UserController extends Controller
{
    /**
     * Update the authenticated user's profile.
     *
     * @authenticated
     *
     * @bodyParam name string optional The user's name. Example: John Doe
     * @bodyParam email string optional The user's email address. Example: john@example.com
     * @bodyParam phone string optional The user's phone number. Example: +2348012345678
     * @bodyParam dob date optional The user's date of birth (YYYY-MM-DD). Example: 1990-01-15
     * @bodyParam gender string optional The user's gender (male, female, or other). Example: male
     *
     * @response 200 scenario="Success" {
     *   "success": true,
     *   "message": "Profile updated successfully",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com",
     *       "phone": "+2348012345678",
     *       "dob": "1990-01-15",
     *       "gender": "male",
     *       "role": "user",
     *       "points": 100,
     *       "avatar": null,
     *       "email_verified_at": "2025-10-18T12:00:00Z",
     *       "created_at": "2025-10-18T12:00:00Z",
     *       "updated_at": "2025-11-23T10:00:00Z"
     *     }
     *   }
     * }
     * @response 422 scenario="Validation Error" {
     *   "success": false,
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "email": ["The email has already been taken."],
     *     "dob": ["The date of birth must be a date before today."],
     *     "gender": ["The gender must be either male, female, or other."]
     *   }
     * }
     * @response 401 scenario="Unauthenticated" {
     *   "message": "Unauthenticated."
     * }
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        $user->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => new UserResource($user->fresh()),
            ],
        ]);
    }

    /**
     * Get a user's profile by ID.
     *
     * @authenticated
     *
     * @urlParam id integer required The ID of the user. Example: 1
     *
     * @response 200 scenario="Success" {
     *   "success": true,
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com",
     *       "phone": "+2348012345678",
     *       "dob": "1990-01-15",
     *       "gender": "male",
     *       "role": "user",
     *       "points": 100,
     *       "avatar": null,
     *       "email_verified_at": "2025-10-18T12:00:00Z",
     *       "created_at": "2025-10-18T12:00:00Z",
     *       "updated_at": "2025-11-23T10:00:00Z"
     *     }
     *   }
     * }
     * @response 404 scenario="Not Found" {
     *   "success": false,
     *   "message": "User not found"
     * }
     * @response 401 scenario="Unauthenticated" {
     *   "message": "Unauthenticated."
     * }
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($user),
            ],
        ]);
    }
}
