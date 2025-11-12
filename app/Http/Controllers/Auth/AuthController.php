<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password as PasswordFacade;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\URL;

/**
 * Controller for handling standard authentication operations
 *
 * This controller provides methods for user registration, login, logout,
 * password reset, and email verification.
 *
 * @group Authentication
 * 
 */
class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: john@example.com
     * @bodyParam password string required The password of the user. Example: password
     * @bodyParam password_confirmation string required The password confirmation. Example: password
     * @bodyParam phone string required The phone number of the user. Example: +1234567890
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Account registered successfully",
     *   "data": {
     *     "user": {}
     *   }
     * }
     * @response 422 {
     *   "success": false,
     *   "message": "The given data was invalid.",
     *   "errors": {}
     * }
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'user',
        ]);

        event(new Registered($user));

        return response()->json([
            'success' => true,
            'message' => 'Account registered successfully',
            'data' => [
                'user' => new UserResource($user)
            ]
        ], 201);
    }

    /**
     * Login user
     *
     * @bodyParam email string required The email of the user. Example: john@example.com
     * @bodyParam password string required The password of the user. Example: password
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Login successful",
     *   "data": {
     *     "user": {},
     *     "token": "1|abcdefghijklmnopqrstuvwxyz"
     *   }
     * }
     * @response 422 {
     *   "success": false,
     *   "message": "Invalid credentials",
     *   "errors": {
     *     "email": ["The provided credentials are incorrect."]
     *   }
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'errors' => [
                    'email' => ['The provided credentials are incorrect.']
                ]
            ], 422);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => new UserResource($user),
                'token' => $token
            ]
        ]);
    }

    /**
     * Logout user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Logged out successfully"
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Unauthenticated."
     * }
     */
    public function logout(Request $request): JsonResponse
    {
        // Delete the current token for Sanctum authentication
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get authenticated user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "user": {}
     *   }
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Unauthenticated."
     * }
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($request->user())
            ]
        ]);
    }

    /**
     * Send password reset link
     *
     * @bodyParam email string required The email of the user. Example: john@example.com
     *
     * @param  \App\Http\Requests\Auth\ForgotPasswordRequest  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Password reset link sent to your email"
     * }
     * @response 400 {
     *   "success": false,
     *   "message": "Failed to send password reset link",
     *   "errors": {}
     * }
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = PasswordFacade::sendResetLink(
            $request->only('email')
        );

        if ($status === PasswordFacade::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent to your email'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send password reset link',
            'errors' => [
                'email' => [__($status)]
            ]
        ], 400);
    }

    /**
     * Verify email
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  string  $hash
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Email verified successfully"
     * }
     * @response 400 {
     *   "success": false,
     *   "message": "Invalid verification link"
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "User not found"
     * }
     */
    public function verifyEmail(Request $request, $id, $hash): JsonResponse
    {
        // Check if the URL is valid
        if (!URL::hasValidSignature($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification link'
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification link'
            ], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified'
            ], 400);
        }

        $user->markEmailAsVerified();

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully'
        ]);
    }

    /**
     * Resend verification email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Verification email resent successfully"
     * }
     * @response 400 {
     *   "success": false,
     *   "message": "Email already verified"
     * }
     */
    public function resendVerificationEmail(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified'
            ], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Verification email resent successfully'
        ]);
    }
}
