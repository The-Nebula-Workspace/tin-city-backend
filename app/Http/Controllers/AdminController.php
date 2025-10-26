<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Route as RouteModel;
use App\Models\Bus;
use App\Models\Contribution;
use App\Http\Resources\UserResource;
use App\Http\Resources\ContributionResource;
use App\Http\Resources\RouteResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Admin endpoints for managing the application.
 *
 * @group Admin
 */
class AdminController extends Controller
{
    /**
     * Get dashboard metrics.
     *
     * @authenticated
     *
     * @response 200 {
     *  "total_users": 10,
     *  "total_routes": 5,
     *  "active_buses": 3
     * }
     *
     * @return JsonResponse
     */
    public function dashboard(): JsonResponse
    {
        return response()->json([
            'total_users' => User::count(),
            'total_routes' => RouteModel::count(),
            'active_buses' => Bus::count(),
        ]);
    }

    /**
     * List all users.
     *
     * @authenticated
     *
     * @return AnonymousResourceCollection
     */
    public function users(): AnonymousResourceCollection
    {
        return UserResource::collection(User::all());
    }

    /**
     * List all contributions.
     *
     * @authenticated
     *
     * @return AnonymousResourceCollection
     */
    public function contributions(): AnonymousResourceCollection
    {
        return ContributionResource::collection(Contribution::all());
    }

    /**
     * List all routes.
     *
     * @authenticated
     *
     * @return AnonymousResourceCollection
     */
    public function routes(): AnonymousResourceCollection
    {
        return RouteResource::collection(RouteModel::all());
    }
}