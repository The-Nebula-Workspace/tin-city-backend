<?php

namespace App\Http\Controllers\Bus;

use App\Http\Controllers\Controller;
use App\Http\Requests\Buses\StoreBusRequest;
use App\Http\Requests\Buses\UpdateBusRequest;
use App\Http\Resources\BusResource;
use App\Http\Resources\RouteResource;
use App\Models\Bus;
use App\Models\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Buses
 *
 * APIs for managing and retrieving buses.
 * Base URL: `/api/v1/buses`
 */
class BusController extends Controller
{
    /**
     * Get all buses. Optionally filter by route ID.
     *
     * @queryParam route_id integer optional Filter by route ID. Example: 1
     *
     * @response 200 {
     *      "success": true,
     *      "message": "Buses retrieved successfully",
     *      "data": [
     *          {
     *          "id": 1,
     *          "route_id": 1,
     *          "name": "TB-001",
     *          "created_at": "2025-11-20T11:14:00.000000Z",
     *          "updated_at": "2025-11-20T11:14:00.000000Z",
     *          "route": {
     *              "id": 1,
     *              "name": "Anguwa to Rayfield",
     *              "start_point": "n",
     *              "end_point": "g",
     *              "encoded_polyline": null,
     *              "distance_km": null,
     *              "created_at": null,
     *              "updated_at": null
     *              }
     *          },
     *      }
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Bus::with('route:id,name,start_point,end_point');

            if ($request->has('route_id')) {
                $query->where('route_id', $request->route_id);
            }

            $buses = $query->orderBy('route_id')->orderBy('name')->get();

            return $this->successResponse(BusResource::collection($buses), 'Buses retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve buses.', 500, $e->getMessage());
        }
    }

    /**
     * Get a specific bus
     *
     * @urlParam bus integer required The bus ID. Example: 1
     *
     * @response 200 {
     *     "success": true,
     *     "message": "Bus retrieved successfully",
     *     "data": {
     *         "id": 16,
     *         "route_id": 3,
     *         "name": "TBL-002",
     *         "created_at": "2025-11-20T11:14:01.000000Z",
     *         "updated_at": "2025-11-20T11:14:01.000000Z",
     *         "route": {
     *             "id": 3,
     *             "name": "Terminus to Barkin Ladi",
     *             "start_point": "Terminus",
     *             "end_point": "Barkin Ladi",
     *             "encoded_polyline": "ohp_Ijk~hEq}@zDc@fBm@tCo@xCr@|Du@hD",
     *             "distance_km": 15.7,
     *             "created_at": "2025-11-20T11:14:00.000000Z",
     *             "updated_at": "2025-11-20T11:14:00.000000Z",
     *             "stops": [
     *                 {
     *                     "id": 14,
     *                     "name": "Terminus",
     *                     "route_id": 3,
     *                     "longitude": "8.85830000",
     *                     "latitude": "9.89650000",
     *                     "order_index": 1,
     *                     "created_at": "2025-11-20T11:14:00.000000Z",
     *                     "updated_at": "2025-11-20T11:14:00.000000Z"
     *                 },
     *                 {
     *                     "id": 15,
     *                     "name": "Bauchi Road",
     *                     "route_id": 3,
     *                     "longitude": "8.87120000",
     *                     "latitude": "9.91560000",
     *                     "order_index": 2,
     *                     "created_at": "2025-11-20T11:14:00.000000Z",
     *                     "updated_at": "2025-11-20T11:14:00.000000Z"
     *                 },
     *                 {
     *                     "id": 16,
     *                     "name": "Hwolshe",
     *                     "route_id": 3,
     *                     "longitude": "8.92340000",
     *                     "latitude": "9.98760000",
     *                     "order_index": 3,
     *                     "created_at": "2025-11-20T11:14:00.000000Z",
     *                     "updated_at": "2025-11-20T11:14:00.000000Z"
     *                 },
     *                 {
     *                     "id": 17,
     *                     "name": "Kuru",
     *                     "route_id": 3,
     *                     "longitude": "8.95670000",
     *                     "latitude": "10.02340000",
     *                     "order_index": 4,
     *                     "created_at": "2025-11-20T11:14:00.000000Z",
     *                     "updated_at": "2025-11-20T11:14:00.000000Z"
     *                 },
     *                 {
     *                     "id": 18,
     *                     "name": "Barkin Ladi Junction",
     *                     "route_id": 3,
     *                     "longitude": "8.89120000",
     *                     "latitude": "9.52340000",
     *                     "order_index": 5,
     *                     "created_at": "2025-11-20T11:14:00.000000Z",
     *                     "updated_at": "2025-11-20T11:14:00.000000Z"
     *                 },
     *                 {
     *                     "id": 19,
     *                     "name": "Barkin Ladi Town",
     *                     "route_id": 3,
     *                     "longitude": "8.88340000",
     *                     "latitude": "9.51230000",
     *                     "order_index": 6,
     *                     "created_at": "2025-11-20T11:14:00.000000Z",
     *                     "updated_at": "2025-11-20T11:14:00.000000Z"
     *                 }
     *             ]
     *         }
     *     }
     * }
     * @response 404 scenario="Not Found" {
     *     "success": false,
     *     "message": "Bus not found",
     *     "errors": null
     * }
     */
    public function show(Bus $bus): JsonResponse
    {
        try {
            $bus->load('route.stops');

            return $this->successResponse(new BusResource($bus), 'Bus retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve bus.', 500, $e->getMessage());
        }
    }

    /**
     * Get buses for a specific route
     *
     * @urlParam route integer required The route ID. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Buses retrieved successfully",
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
        try {
            $buses = $route->buses()->orderBy('name')->get();

            return $this->successResponse([
                'route' => new RouteResource($route),
                'buses' => BusResource::collection($buses),
                'total_buses' => $buses->count(),
            ], 'Buses retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve buses.', 500, $e->getMessage());
        }
    }

    /**
     * Create a new bus
     *
     * @authenticated
     *
     * @bodyParam route_id integer required The route ID. Example: 1
     * @bodyParam name string required The name of the bus. Example: TB-001
     *
     * @response 200 {
     *     "success": true,
     *     "message": "Bus created successfully",
     *     "data": {
     *         "id": 1,
     *         "route_id": 1,
     *         "name": "TB-001",
     *         "created_at": "2025-11-20T11:14:00.000000Z",
     *         "updated_at": "2025-11-20T11:14:00.000000Z"
     *     }
     * }
     * @response 422 scenario="Validation Error" {
     *     "success": false,
     *     "message": "Validation error.",
     *     "errors": {
     *         "name": ["The name field is required."]
     *     }
     * }
     */
    public function store(StoreBusRequest $request): JsonResponse
    {
        try {
            $bus = Bus::create($request->validated());

            return $this->successResponse(new BusResource($bus), 'Bus created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create bus.', 500, $e->getMessage());
        }
    }

    /**
     * Update a bus
     *
     * @authenticated
     *
     * @urlParam bus integer required The bus ID. Example: 1
     *
     * @bodyParam name string optional The name of the bus. Example: TB-001
     *
     * @response 200 {
     *     "success": true,
     *     "message": "Bus updated successfully",
     *     "data": {
     *         "id": 1,
     *         "route_id": 1,
     *         "name": "TB-001",
     *         "created_at": "2025-11-20T11:14:00.000000Z",
     *         "updated_at": "2025-11-20T11:14:00.000000Z"
     *     }
     * }
     * @response 422 scenario="Validation Error" {
     *     "success": false,
     *     "message": "Validation error.",
     *     "errors": {
     *         "name": ["The name field is required."]
     *     }
     * }
     * @response 404 scenario="Not Found" {
     *     "success": false,
     *     "message": "Bus not found",
     *     "errors": null
     * }
     */
    public function update(UpdateBusRequest $request, Bus $bus): JsonResponse
    {
        try {
            $bus->update($request->validated());

            return $this->successResponse(new BusResource($bus), 'Bus updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update bus.', 500, $e->getMessage());
        }
    }

    /**
     * Delete a bus
     *
     * @authenticated
     *
     * @urlParam bus integer required The bus ID. Example: 1
     *
     * @response 200 {
     *     "success": true,
     *     "message": "Bus deleted successfully",
     *     "data": null
     * }
     * @response 404 scenario="Not Found" {
     *     "success": false,
     *     "message": "Bus not found",
     *     "errors": null
     * }
     */
    public function destroy(Bus $bus): JsonResponse
    {
        try {
            $bus->delete();

            return $this->successResponse(null, 'Bus deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete bus.', 500, $e->getMessage());
        }
    }
}
