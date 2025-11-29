<?php

namespace App\Http\Controllers\Route;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRouteRequest;
use App\Http\Requests\UpdateRouteRequest;
use App\Http\Resources\RouteResource;
use App\Models\Route;
use App\Services\RouteService;
use Illuminate\Http\JsonResponse;

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
     *   "success": true,
     *   "message": "Routes retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Anguwa to Rayfield",
     *       "start_point": "n",
     *       "end_point": "g",
     *       "encoded_polyline": "architecto",
     *       "distance_km": 39,
     *       "created_at": "2025-11-20T11:14:00.000000Z",
     *       "updated_at": "2025-11-20T12:09:37.000000Z",
     *       "stops": [
     *         {
     *           "id": 47,
     *           "name": "architecto",
     *           "route_id": 1,
     *           "longitude": "-3.41688000",
     *           "latitude": "2.41688000",
     *           "order_index": 16,
     *           "created_at": "2025-11-20T12:09:48.000000Z",
     *           "updated_at": "2025-11-20T12:09:48.000000Z"
     *         }
     *       ],
     *       "contributions": [
     *         {
     *           "id": 1,
     *           "user_id": 3,
     *           "route_id": 1,
     *           "type": "location",
     *           "data": {
     *             "latitude": 9.8965,
     *             "longitude": 8.8583,
     *             "speed": 45.5,
     *             "heading": 180,
     *             "accuracy": 10.5
     *           },
     *           "created_at": "2025-11-20T11:26:54.000000Z",
     *           "updated_at": "2025-11-20T11:26:54.000000Z"
     *         },
     *         {
     *           "id": 6,
     *           "user_id": 7,
     *           "route_id": 1,
     *           "type": "crowding",
     *           "data": {
     *             "status": "standing",
     *             "crowding_level": 3,
     *             "latitude": 9.8965,
     *             "longitude": 8.8583
     *           },
     *           "created_at": "2025-11-29T15:33:16.000000Z",
     *           "updated_at": "2025-11-29T15:33:16.000000Z"
     *         }
     *       ]
     *     }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        try {
            return $this->successResponse(
                RouteResource::collection($this->route_service->getAllRoutes()),
                'Routes retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve routes.', 500, $e->getMessage());
        }
    }

    /**
     * Store a newly created route (Admin only).
     *
     * @authenticated
     *
     * @bodyParam name string required The name of the route. Example: Terminus to Bukuru
     * @bodyParam encoded_polyline string required Google Maps encoded polyline representing the route path. Example: mfp_Ijk~hEo}@yDa@e...
     * @bodyParam distance float required The total distance (in km). Example: 8.3
     * @bodyParam stops array optional List of stops related to the route.
     *
     * @response 201 scenario="Created" {
     *   "success": true,
     *   "message": "Route created successfully",
     *   "data": {
     *     "id": 12,
     *     "name": "Terminus to Anguwan Rimi",
     *     "start_point": "Terminus",
     *     "end_point": "Anguwan Rimi",
     *     "encoded_polyline": "mfp_Ijk~hEo}@yDa@e..",
     *     "distance_km": 39,
     *     "created_at": "2025-11-29T17:05:55.000000Z",
     *     "updated_at": "2025-11-29T17:05:55.000000Z",
     *     "stops": [
     *       {
     *         "id": 48,
     *         "name": "Terminus",
     *         "route_id": 12,
     *         "longitude": "12.50000000",
     *         "latitude": "10.00000000",
     *         "order_index": 1,
     *         "created_at": "2025-11-29T17:05:55.000000Z",
     *         "updated_at": "2025-11-29T17:05:55.000000Z"
     *       }
     *     ]
     *   }
     * }
     *
     * @response 422 scenario="Validation Error" {
     *   "success": false,
     *   "message": "Validation error.",
     *   "errors": {
     *     "name": ["The name field is required."]
     *   }
     * }
     */
    public function store(StoreRouteRequest $request): JsonResponse
    {
        try {
            $route = $this->route_service->create($request->validated());

            return $this->successResponse(
                new RouteResource($route),
                'Route created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create route.', 500, $e->getMessage());
        }
    }

    /**
     * Display a specific route by ID.
     *
     * @urlParam id integer required The ID of the route. Example: 1
     *
     * @response 200 scenario="Success" {
     *   "success": true,
     *   "message": "Route retrieved successfully",
     *   "data": {
     *     "id": 12,
     *     "name": "Terminus to Anguwan Rimi",
     *     "start_point": "Terminus",
     *     "end_point": "Anguwan Rimi",
     *     "encoded_polyline": "mfp_Ijk~hEo}@yDa@e..",
     *     "distance_km": 39,
     *     "created_at": "2025-11-29T17:05:55.000000Z",
     *     "updated_at": "2025-11-29T17:05:55.000000Z",
     *     "stops": [
     *       {
     *         "id": 48,
     *         "name": "Terminus",
     *         "route_id": 12,
     *         "longitude": "12.50000000",
     *         "latitude": "10.00000000",
     *         "order_index": 1,
     *         "created_at": "2025-11-29T17:05:55.000000Z",
     *         "updated_at": "2025-11-29T17:05:55.000000Z"
     *       }
     *     ]
     *   }
     * }
     * @response 404 scenario="Not Found" {
     *   "success": false,
     *   "message": "Route not found",
     *   "errors": []
     * }
     */
    public function show(Route $route): JsonResponse
    {
        try {
            return $this->successResponse(
                new RouteResource($route),
                'Route retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve route.', 500, $e->getMessage());
        }
    }

    /**
     * Update an existing route (Admin only).
     *
     * @authenticated
     *
     * @urlParam route integer required The ID of the route to update. Example: 1
     *
     * @bodyParam name string optional The updated name of the route.
     * @bodyParam encoded_polyline string optional Updated Google Maps polyline.
     * @bodyParam distance float optional Updated distance (in km).
     *
     * @response 200 scenario="Updated" {
     *   "success": true,
     *   "message": "Route updated successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Terminus to Bukuru (Updated)",
     *     "start_point": "Terminus",
     *     "end_point": "Bukuru (Updated)",
     *     "encoded_polyline": "xyz123...",
     *     "distance_km": 9.1,
     *     "created_at": "2025-11-29T17:05:55.000000Z",
     *     "updated_at": "2025-11-29T17:05:55.000000Z",
     *     "stops": [
     *       {
     *         "id": 48,
     *         "name": "Terminus",
     *         "route_id": 12,
     *         "longitude": "12.50000000",
     *         "latitude": "10.00000000",
     *         "order_index": 1,
     *         "created_at": "2025-11-29T17:05:55.000000Z",
     *         "updated_at": "2025-11-29T17:05:55.000000Z"
     *       }
     *     ]
     *   }
     * }
     * @response 422 scenario="Validation Error" {
     *   "success": false,
     *   "message": "Validation error.",
     *   "errors": {
     *     "name": ["The name field is required."]
     *   }
     * }
     * @response 404 scenario="Not Found" {
     *   "success": false,
     *   "message": "Route not found",
     *   "errors": []
     * }
     */
    public function update(UpdateRouteRequest $request, Route $route): JsonResponse
    {
        try {
            $updated = $this->route_service->update($route, $request->validated());

            return $this->successResponse(
                new RouteResource($updated),
                'Route updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update route.', 500, $e->getMessage());
        }
    }

    /**
     * Delete a route (Admin only).
     *
     * @authenticated
     *
     * @urlParam route integer required The ID of the route to delete. Example: 1
     *
     * @response 200 scenario="Deleted" {
     *   "success": true,
     *   "message": "Route deleted successfully",
     * }
     * @response 404 scenario="Not Found" {
     *   "success": false,
     *   "message": "Route not found",
     *   "errors": []
     * }
     */
    public function destroy(Route $route): JsonResponse
    {
        try {
            $this->route_service->delete($route);

            return $this->successResponse(null, 'Route deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete route.', 500, $e->getMessage());
        }
    }

    /**
     * Export all static routes in JSON format for mobile app usage.
     *
     * @response 200 scenario="Success" {
     *   "success": true,
     *   "message": "Routes exported successfully",
     *   "exported_at": "2025-10-18T15:00:00Z",
     *   "routes": [
     *     {
     *       "id": 1,
     *       "name": "Terminus to Bukuru",
     *       "start_point": "Terminus",
     *       "end_point": "Bukuru",
     *       "encoded_polyline": "mfp_Ijk~hEo}@yDa@e..",
     *       "distance_km": 39,
     *       "created_at": "2025-11-29T17:05:55.000000Z",
     *       "updated_at": "2025-11-29T17:05:55.000000Z"
     *     }
     *   ]
     * }
     */
    public function export(): JsonResponse
    {
        try {
            $routes = $this->route_service->getAllRoutes();

            return $this->successResponse([
                'exported_at' => now(),
                'routes' => RouteResource::collection($routes),
            ], 'Routes exported successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to export routes.', 500, $e->getMessage());
        }
    }
}
