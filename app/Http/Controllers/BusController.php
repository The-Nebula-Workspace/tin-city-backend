<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Route;
use App\Http\Resources\BusResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Bus::with('route:id,name,start_point,end_point');

        if ($request->has('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        $buses = $query->orderBy('route_id')->orderBy('name')->get();

        return BusResource::collection($buses);
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
    public function show(Bus $bus): BusResource
    {
        $bus->load('route.stops');

        return new BusResource($bus);
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
                'route' => [
                    'id' => $route->id,
                    'name' => $route->name,
                    'start_point' => $route->start_point,
                    'end_point' => $route->end_point,
                ],
                'buses' => BusResource::collection($buses),
                'total_buses' => $buses->count(),
            ],
        ]);
    }
}
