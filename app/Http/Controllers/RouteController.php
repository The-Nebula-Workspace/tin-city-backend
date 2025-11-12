<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\RouteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\RouteResource;
use App\Http\Requests\StoreRouteRequest;
use App\Http\Requests\UpdateRouteRequest;

/**
 * @group Routes Management
 *
 * APIs for managing and retrieving bus routes within the Jos Metro BOSS system.
 *
 * These endpoints handle both public and admin operations for static routes.
 *
 * Base URL: `/api/v1/routes`
 */
class RouteController extends Controller
{
    private RouteService $route_service;
    /**
     * Inject dependencies.
     */
    public function __construct(RouteService $route_service)
    {
        $this->route_service = $route_service;
    }

    /**
     * Display a listing of all available routes.
     *
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Terminus to Bukuru",
     *       "encoded_polyline": "mfp_Ijk~hE...",
     *       "distance": 8.3,
     *       "created_at": "2025-10-18T12:00:00Z"
     *     }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        return response()->json(
            RouteResource::collection($this->route_service->getAllRoutes()),
            Response::HTTP_OK
        );
    }

    /**
     * Store a newly created route (Admin only).
     *
     * @authenticated
     *
     * @bodyParam name string required The name of the route. Example: Terminus to Bukuru
     * @bodyParam encoded_polyline string required Google Maps encoded polyline representing the route path. Example: mfp_Ijk~hEo}@yDa@e...
     * @bodyParam distance float required The total distance (in km). Example: 8.3
     * @bodyParam stops array Optional list of stops related to the route.
     *
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": 1,
     *     "name": "Terminus to Bukuru",
     *     "encoded_polyline": "mfp_Ijk~hE...",
     *     "distance": 8.3,
     *     "created_at": "2025-10-18T12:00:00Z"
     *   }
     * }
     */
    public function store(StoreRouteRequest $request): JsonResponse
    {
        $route = $this->route_service->create($request->validated());

        return response()->json(
            new RouteResource($route),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display a specific route by ID.
     *
     * @urlParam id integer required The ID of the route. Example: 1
     *
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": 1,
     *     "name": "Terminus to Bukuru",
     *     "encoded_polyline": "mfp_Ijk~hE...",
     *     "distance": 8.3,
     *     "created_at": "2025-10-18T12:00:00Z"
     *   }
     * }
     * @response 404 scenario="Not Found" {"error": "Route not found"}
     */
    public function show(string $id): JsonResponse
    {
        $route = $this->route_service->findById($id);

        return response()->json(
            new RouteResource($route),
            Response::HTTP_OK
        );
    }

    /**
     * Update an existing route (Admin only).
     *
     * @authenticated
     *
     * @urlParam route integer required The ID of the route to update. Example: 1
     * @bodyParam name string optional The updated name of the route.
     * @bodyParam encoded_polyline string optional Updated Google Maps polyline.
     * @bodyParam distance float optional Updated distance (in km).
     *
     * @response 200 scenario="Updated" {
     *   "data": {
     *     "id": 1,
     *     "name": "Terminus to Bukuru (Updated)",
     *     "encoded_polyline": "xyz123...",
     *     "distance": 9.1
     *   }
     * }
     * @response 403 scenario="Forbidden" {"error": "You are not authorized to perform this action"}
     */
    public function update(UpdateRouteRequest $request, Route $route): JsonResponse
    {
        $updated = $this->route_service->update($route, $request->validated());

        return response()->json(
            new RouteResource($updated),
            Response::HTTP_OK
        );
    }

    /**
     * Delete a route (Admin only).
     *
     * @authenticated
     *
     * @urlParam route integer required The ID of the route to delete. Example: 1
     * @response 200 scenario="Deleted" {"message": "Route deleted successfully"}
     * @response 404 scenario="Not Found" {"error": "Route not found"}
     */
    public function destroy(Route $route): JsonResponse
    {
        if (!$route) {
            return response()->json(['error' => 'Route not found'], Response::HTTP_NOT_FOUND);
        }

        $this->route_service->delete($route);

        return response()->json(
            ['message' => 'Route deleted successfully'],
            Response::HTTP_OK
        );
    }

    /**
     * Export all static routes in JSON format for mobile app usage.
     *
     * @response 200 scenario="Success" {
     *   "exported_at": "2025-10-18T15:00:00Z",
     *   "routes": [...]
     * }
     */
    public function export(): JsonResponse
    {
        $routes = $this->route_service->getAllRoutes();
        return response()->json([
            'exported_at' => now(),
            'routes' => RouteResource::collection($routes),
        ]);
    }


    /**
     * Authenticate a user (testing endpoint).
     *
     * @bodyParam email string required The user's email address. Example: test@example.com
     * @bodyParam password string required The user's password. Example: password
     *
     * @response 200 scenario="Success" {
     *   "user": {
     *     "id": 1,
     *     "name": "Test Admin",
     *     "email": "test@example.com",
     *     "role": "admin"
     *   },
     *   "token": "1|XyzABC123..."
     * }
     * @response 401 scenario="Unauthorized" {"message": "Invalid credentials"}
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
