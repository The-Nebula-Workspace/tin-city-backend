<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Buses
 *
 * APIs for managing and retrieving buses
 */
class BusController extends Controller
{
    /**
     * Get all buses
     *
     * @queryParam route_id integer optional Filter by route ID. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "TB-001",
     *       "route_id": 1,
     *       "route": {
     *         "id": 1,
     *         "name": "Terminus to Bukuru"
     *       }
     *     }
     *   ]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $query = Bus::with('route:id,name,start_point,end_point');

        if ($request->has('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        $buses = $query->orderBy('route_id')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $buses,
            'total' => $buses->count(),
        ]);
    }

    /**
     * Get a specific bus
     *
     * @urlParam id integer required The bus ID. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "id": 1,
     *     "name": "TB-001",
     *     "route_id": 1,
     *     "route": {
     *       "id": 1,
     *       "name": "Terminus to Bukuru",
     *       "stops": []
     *     }
     *   }
     * }
     */
    public function show(int $id): JsonResponse
    {
        $bus = Bus::with('route.stops')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $bus,
        ]);
    }

    /**
     * Get buses for a specific route
     *
     * @urlParam routeId integer required The route ID. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "route": {
     *       "id": 1,
     *       "name": "Terminus to Bukuru"
     *     },
     *     "buses": [
     *       {
     *         "id": 1,
     *         "name": "TB-001"
     *       }
     *     ],
     *     "total_buses": 8
     *   }
     * }
     */
    public function getByRoute(int $routeId): JsonResponse
    {
        $route = Route::findOrFail($routeId);
        $buses = Bus::where('route_id', $routeId)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'route' => [
                    'id' => $route->id,
                    'name' => $route->name,
                    'start_point' => $route->start_point,
                    'end_point' => $route->end_point,
                ],
                'buses' => $buses,
                'total_buses' => $buses->count(),
            ],
        ]);
    }
}
