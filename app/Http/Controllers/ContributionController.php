<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Route;
use App\Services\BusTrackingService;
use App\Events\BusLocationUpdated;
use App\Events\BusCrowdingUpdated;
use App\Http\Requests\SubmitLocationRequest;
use App\Http\Requests\SubmitCrowdingRequest;
use App\Http\Requests\GetLatestContributionsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @group Contributions
 *
 * APIs for users to contribute real-time bus data
 */
class ContributionController extends Controller
{
    private BusTrackingService $busTrackingService;

    public function __construct(BusTrackingService $busTrackingService)
    {
        $this->busTrackingService = $busTrackingService;
    }

    /**
     * Submit bus location contribution
     *
     * @authenticated
     *
     * @bodyParam route_id integer required The route ID. Example: 1
     * @bodyParam latitude float required Current latitude. Example: 9.8965
     * @bodyParam longitude float required Current longitude. Example: 8.8583
     * @bodyParam speed float optional Speed in km/h. Example: 45.5
     * @bodyParam heading float optional Direction in degrees. Example: 180
     * @bodyParam accuracy float optional GPS accuracy in meters. Example: 10.5
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Location contribution recorded",
     *   "data": {
     *     "contribution_id": 1,
     *     "is_on_route": true,
     *     "active_buses_count": 3
     *   }
     * }
     */
    public function submitLocation(SubmitLocationRequest $request): JsonResponse
    {
        $user = Auth::user();
        $routeId = $request->route_id;

        // Get route to validate location
        $route = Route::with('stops')->findOrFail($routeId);

        $locationData = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'speed' => $request->speed,
            'heading' => $request->heading,
            'accuracy' => $request->accuracy,
        ];

        // Validate user is on route (using stops as reference points)
        $routeCoordinates = $route->stops->map(fn($stop) => [
            'latitude' => (float) $stop->latitude,
            'longitude' => (float) $stop->longitude,
        ])->toArray();

        $isOnRoute = empty($routeCoordinates) ? true :
            $this->busTrackingService->validateUserOnRoute($locationData, $routeCoordinates);

        // Store in contributions table
        $contribution = Contribution::create([
            'user_id' => $user->id,
            'route_id' => $routeId,
            'type' => 'location',
            'data' => $locationData,
        ]);

        // Store in Redis for real-time tracking
        if ($isOnRoute) {
            $this->busTrackingService->storeActiveBusLocation(
                $user->id,
                $routeId,
                $locationData
            );

            // Broadcast location update via WebSocket
            broadcast(new BusLocationUpdated($user->id, $routeId, $locationData));

            // Award points for contribution
            $user->addPoints(5);
        }

        $activeBuses = $this->busTrackingService->getActiveBusesForRoute($routeId);

        return response()->json([
            'success' => true,
            'message' => 'Location contribution recorded',
            'data' => [
                'contribution_id' => $contribution->id,
                'is_on_route' => $isOnRoute,
                'active_buses_count' => count($activeBuses),
            ]
        ], 201);
    }

    /**
     * Submit bus crowding report
     *
     * @authenticated
     *
     * @bodyParam route_id integer required The route ID. Example: 1
     * @bodyParam status string required Bus status. Example: standing
     * @bodyParam crowding_level integer required Crowding level 1-5. Example: 3
     * @bodyParam latitude float required Current latitude. Example: 9.8965
     * @bodyParam longitude float required Current longitude. Example: 8.8583
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Crowding report submitted",
     *   "data": {
     *     "contribution_id": 2
     *   }
     * }
     */
    public function submitCrowding(SubmitCrowdingRequest $request): JsonResponse
    {
        $user = Auth::user();

        $crowdingData = [
            'status' => $request->status,
            'crowding_level' => $request->crowding_level,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        // Store in contributions table
        $contribution = Contribution::create([
            'user_id' => $user->id,
            'route_id' => $request->route_id,
            'type' => 'crowding',
            'data' => $crowdingData,
        ]);

        // Broadcast crowding update via WebSocket
        broadcast(new BusCrowdingUpdated(
            $request->route_id,
            $request->status,
            $request->crowding_level,
            $crowdingData
        ));

        // Award points for contribution
        $user->addPoints(3);

        return response()->json([
            'success' => true,
            'message' => 'Crowding report submitted',
            'data' => [
                'contribution_id' => $contribution->id,
            ]
        ], 201);
    }

    /**
     * Get latest contributions for a route
     *
     * @queryParam route_id integer required The route ID. Example: 1
     * @queryParam type string optional Filter by type (location, crowding). Example: location
     * @queryParam limit integer optional Number of results. Example: 20
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "contributions": [],
     *     "active_buses": []
     *   }
     * }
     */
    public function getLatest(GetLatestContributionsRequest $request): JsonResponse
    {
        $routeId = $request->route_id;
        $type = $request->type;
        $limit = $request->limit ?? 20;

        // Get contributions from database
        $query = Contribution::where('route_id', $routeId)
            ->with('user:id,name,avatar')
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if ($type) {
            $query->where('type', $type);
        }

        $contributions = $query->get();

        // Get active buses from Redis
        $activeBuses = $this->busTrackingService->getActiveBusesForRoute($routeId);

        return response()->json([
            'success' => true,
            'data' => [
                'contributions' => $contributions,
                'active_buses' => $activeBuses,
                'active_buses_count' => count($activeBuses),
            ]
        ]);
    }
}
