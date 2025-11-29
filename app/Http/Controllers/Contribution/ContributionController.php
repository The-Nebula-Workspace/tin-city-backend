<?php

namespace App\Http\Controllers\Contribution;

use App\Events\BusCrowdingUpdated;
use App\Events\BusLocationUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetLatestContributionsRequest;
use App\Http\Requests\SubmitCrowdingRequest;
use App\Http\Requests\SubmitLocationRequest;
use App\Http\Resources\ContributionResource;
use App\Models\Contribution;
use App\Models\Route;
use App\Services\BadgeService;
use App\Services\BusTrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @group Contributions
 *
 * APIs for users to contribute real-time bus data
 * Base URL: `/api/v1/contributions`
 */
class ContributionController extends Controller
{
    private BusTrackingService $busTrackingService;

    private BadgeService $badgeService;

    public function __construct(BusTrackingService $busTrackingService, BadgeService $badgeService)
    {
        $this->busTrackingService = $busTrackingService;
        $this->badgeService = $badgeService;
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
     * @response 201 scenario="Success" {
     *   "success": true,
     *   "message": "Location contribution recorded",
     *   "data": {
     *     "contribution_id": 1,
     *     "is_on_route": true,
     *     "active_buses_count": 3
     *   }
     * }
     * @response 422 scenario="Validation Error" {
     *   "success": false,
     *   "message": "Validation error.",
     *   "errors": {
     *     "route_id": ["The route ID is required."]
     *   }
     * }
     */
    public function submitLocation(SubmitLocationRequest $request): JsonResponse
    {
        try {
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
            $routeCoordinates = $route->stops->map(fn ($stop) => [
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
                $user->addPoints(config('rewards.actions.location_share'));

                $this->badgeService->checkAndAwardBadges($user);
            }

            $activeBuses = $this->busTrackingService->getActiveBusesForRoute($routeId);

            return $this->successResponse([
                'contribution_id' => $contribution->id,
                'is_on_route' => $isOnRoute,
                'active_buses_count' => count($activeBuses),
            ], 'Location contribution recorded', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to submit location contribution.', 500, $e->getMessage());
        }
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
     * @response 201 scenario="Success" {
     *   "success": true,
     *   "message": "Crowding report submitted",
     *   "data": {
     *     "contribution_id": 2
     *   }
     * }
     * @response 422 scenario="Validation Error" {
     *   "success": false,
     *   "message": "Validation error.",
     *   "errors": {
     *     "route_id": ["The route ID is required."]
     *   }
     * }
     */
    public function submitCrowding(SubmitCrowdingRequest $request): JsonResponse
    {
        try {
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
            $user->addPoints(config('rewards.actions.crowd_report'));
            $this->badgeService->checkAndAwardBadges($user);

            return $this->successResponse([
                'contribution_id' => $contribution->id,
            ], 'Crowding report submitted', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to submit crowding report.', 500, $e->getMessage());
        }
    }

    /**
     * Get latest contributions for a route
     *
     * @queryParam route_id integer required The route ID. Example: 1
     * @queryParam type string optional Filter by type (location, crowding). Example: location
     * @queryParam limit integer optional Number of results. Example: 20
     *
     * @response 200 scenario="Success" {
     *   "success": true,
     *   "data": {
     *     "contributions": [],
     *     "active_buses": [],
     *     "active_buses_count": 3
     *   }
     * }
     * @response 422 scenario="Validation Error" {
     *   "success": false,
     *   "message": "Validation error.",
     *   "errors": {
     *     "route_id": ["The route ID is required."]
     *   }
     * }
     */
    public function getLatest(GetLatestContributionsRequest $request): JsonResponse
    {
        try {
            $routeId = $request->validated('route_id');
            $type = $request->validated('type');
            $limit = $request->validated('limit') ?? 20;

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

            return $this->successResponse([
                'contributions' => ContributionResource::collection($contributions),
                'active_buses' => $activeBuses,
                'active_buses_count' => count($activeBuses),
            ], 'Latest contributions retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve latest contributions.', 500, $e->getMessage());
        }
    }
}
