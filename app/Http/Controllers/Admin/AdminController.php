<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContributionResource;
use App\Http\Resources\RouteResource;
use App\Http\Resources\UserResource;
use App\Models\Bus;
use App\Models\Contribution;
use App\Models\Route as RouteModel;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * @group Admin Dashboard
 * Endpoints to retrieve admin dashboard metrics.
 *
 * Base URL: `/api/v1/admin`
 */
class AdminController extends Controller
{
    /**
     * Get high-level metrics for the admin dashboard.
     *
     * Returns total numbers of users, routes, and active buses in the system.
     *
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Dashboard metrics retrieved successfully",
     *   "data": {
     *     "total_users": 1200,
     *     "total_routes": 42,
     *     "active_buses": 18
     *   }
     * }
     * @response 401 {"message": "Unauthenticated."}
     * @response 403 {"message": "This action is unauthorized."}
     */
    public function dashboard(): JsonResponse
    {
        try {
            return $this->successResponse([
                'total_users' => User::count(),
                'total_routes' => RouteModel::count(),
                'active_buses' => Bus::count(),
            ],
                'Dashboard metrics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve dashboard metrics.', 500, $e->getMessage());
        }
    }

    /**
     * List all users.
     *
     * Returns a collection of users wrapped in the standard API envelope.
     * Results are transformed using the `UserResource`.
     *
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Users retrieved successfully",
     *   "data": [
     *     {
     *      "id": 1,
     *      "name": "John Doe",
     *      "email": "john@example.com",
     *      "phone": "+2348034567890",
     *      "dob": null,
     *      "gender": null,
     *      "role": "user",
     *      "points": 0,
     *      "avatar": null,
     *      "email_verified_at": null,
     *      "created_at": "2025-10-27T08:17:57.000000Z",
     *      "updated_at": "2025-10-27T08:17:57.000000Z"
     *     }
     *   ]
     * }
     * @response 401 {"message": "Unauthenticated."}
     * @response 403 {"message": "This action is unauthorized."}
     */
    public function users(): JsonResponse
    {
        try {
            return $this->successResponse(UserResource::collection(User::all()), 'Users retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve users.', 500, $e->getMessage());
        }
    }

    /**
     * List all contributions.
     *
     * Returns a collection of user contributions wrapped in the standard API envelope.
     * Results are transformed using the `ContributionResource`.
     *
     * @authenticatedF
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Contributions retrieved successfully",
     *   "data": [
     *        {
     *          "id": 3,
     *          "user_id": 3,
     *          "route_id": 2,
     *          "type": "location",
     *          "data": {
     *              "latitude": 9.8965,
     *              "longitude": 8.8583,
     *              "speed": 45.5,
     *              "heading": 180,
     *              "accuracy": 10.5
     *          },
     *          "created_at": "2025-11-20T11:31:34.000000Z",
     *          "updated_at": "2025-11-20T11:31:34.000000Z"
     *       }
     *   ]
     * }
     * @response 401 {"message": "Unauthenticated."}
     * @response 403 {"message": "This action is unauthorized."}
     */
    public function contributions(): JsonResponse
    {
        try {
            return $this->successResponse(ContributionResource::collection(Contribution::all()), 'Contributions retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve contributions.', 500, $e->getMessage());
        }
    }

    /**
     * List all routes.
     *
     * Returns a collection of transport routes wrapped in the standard API envelope.
     * Results are transformed using the `RouteResource`.
     *
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Routes retrieved successfully",
     *   "data": [
     *      {
     *          "id": 4,
     *          "name": "Bukuru to Rayfield",
     *          "start_point": "Bukuru",
     *          "end_point": "Rayfield",
     *          "encoded_polyline": "mep_Ihk~hEn}@wDa@cBk@qCm@uCq@yD",
     *          "distance_km": 5.2,
     *          "created_at": "2025-11-20T11:14:00.000000Z",
     *          "updated_at": "2025-11-20T11:14:00.000000Z"
     *      }
     *   ]
     * }
     * @response 401 {"message": "Unauthenticated."}
     * @response 403 {"message": "This action is unauthorized."}
     */
    public function routes(): JsonResponse
    {
        try {
            return $this->successResponse(RouteResource::collection(RouteModel::all()), 'Routes retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve routes.', 500, $e->getMessage());
        }
    }
}
