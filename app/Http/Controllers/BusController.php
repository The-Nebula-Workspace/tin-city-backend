<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Route;
use App\Http\Resources\BusResource;
use App\Http\Resources\RouteResource;
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
            'data' => BusResource::collection($buses),
        ]);
    }

    /**
     * Get a specific bus
     *
     * @urlParam bus integer required The bus ID. Example: 1
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
    public function show(Bus $bus): JsonResponse
    {
        $bus->load('route.stops');

        return response()->json([
            'success' => true,
            'data' => new BusResource($bus),
        ]);
    }

    /**
     * Get buses for a specific route
     *
     * @urlParam route integer required The route ID. Example: 1
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
    public function getByRoute(Route $route): JsonResponse
    {
        $buses = $route->buses()->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'route' => new RouteResource($route),
                'buses' => BusResource::collection($buses),
                'total_buses' => $buses->count(),
            ],
        ]);
    }
}
