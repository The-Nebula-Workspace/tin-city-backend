<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Controller for handling Google OAuth authentication
 *
 * This controller provides methods for redirecting users to Google for authentication
 * and handling the callback to create or authenticate users in the system.
 *
 * @group Google Authentication
 */
class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google for authentication
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @response 302 {
     *   "Redirects to Google OAuth page"
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Failed to redirect to Google",
     *   "error": "Error message"
     * }
     */
    public function redirectToGoogle(): JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
    {
        try {
            $redirect = Socialite::driver('google')->stateless()->redirect();
            return $redirect;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to redirect to Google',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Google authentication callback and create or update user
     *
     * This method handles the callback from Google OAuth, either creating a new user
     * or updating an existing user with their Google ID. It also creates an API token
     * for authentication.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Google authentication successful",
     *   "data": {
     *     "user": {},
     *     "token": "1|abcdefghijklmnopqrstuvwxyz"
     *   }
     * }
     * @response 400 {
     *   "success": false,
     *   "message": "Google authentication failed",
     *   "error": "Error message",
     *   "file": "/path/to/file.php",
     *   "line": 123
     * }
     */
    public function handleGoogleCallback(): JsonResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(uniqid()),
                    'email_verified_at' => now(),
                ]
            );

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Google authentication successful',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Google authentication failed',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 400);
        }
    }
}
