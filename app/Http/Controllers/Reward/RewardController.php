<?php

namespace App\Http\Controllers\Reward;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rewards\AwardPointsRequest;
use App\Services\RewardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Rewards & Points System
 *
 * APIs for retrieving and managing user reward points and reward history.
 *
 * These endpoints handle how users earn and view their Tin City Metro rewards.
 *
 * Base URL: `/api/rewards`
 */
class RewardController extends Controller
{
    protected $rewardService;

    /**
     * Inject the RewardService dependency.
     */
    public function __construct(RewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    /**
     * Get total reward points.
     *
     * Retrieve the user’s total accumulated reward points.
     *
     * @authenticated
     *
     * @response 200 scenario="Success" {
     *   "data": {
     *     "user_id": 1,
     *     "total_points": 15,
     *     "description": "Total reward points"
     *   },
     *   "message": "Reward balance retrieved successfully"
     * }
     * @response 200 scenario="No rewards" {
     *   "data": null,
     *   "message": "No rewards yet"
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->rewardService->getUserRewards($request->user());

        return $this->successResponse(
            $data,
            'No rewards yet'
        );
    }

    /**
     * Get reward transaction history.
     *
     * Retrieve a paginated list of all actions that earned the user points.
     *
     * @authenticated
     *
     * @queryParam page integer optional The page number for pagination. Example: 1
     *
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "user_id": 1,
     *       "action": "crowd_report",
     *       "points": 3,
     *       "description": "Reported bus crowding",
     *       "created_at": "2025-10-23T10:00:00Z"
     *     },
     *     {
     *       "id": 2,
     *       "user_id": 1,
     *       "action": "share_location",
     *       "points": 4,
     *       "description": "Shared location data",
     *       "created_at": "2025-10-23T11:00:00Z"
     *     }
     *   ],
     *   "message": "Reward history retrieved successfully"
     * }
     * @response 200 scenario="No history" {
     *   "data": [],
     *   "message": "No reward history found"
     * }
     */
    public function history(Request $request): JsonResponse
    {
        $history = $this->rewardService->getUserHistory($request->user());

        return $this->successResponse(
            $history,
            'Reward history retrieved successfully'
        );
    }

    /**
     * Award points for user actions.
     *
     * Award points to the authenticated user for performing specific actions like sharing location or reporting crowd.
     *
     * @authenticated
     *
     * @bodyParam action string required The action that earned points. Example: location_share
     * @bodyParam description string optional Description of the action. Example: Shared my bus location
     * @bodyParam prevent_duplicate boolean optional Whether to prevent duplicate rewards. Default: false
     *
     * @response 200 scenario="Success" {
     *   "message": "Points awarded successfully",
     *   "points": 3
     * }
     * @response 400 scenario="Invalid action" {
     *   "message": "Invalid action or no points awarded"
     * }
     * @response 422 scenario="Validation error" {
     *   "message": "The action field is required."
     * }
     */
    public function awardPoints(AwardPointsRequest $request): JsonResponse
    {
        $user = $request->user();

        $action = $request->input('action');
        $description = $request->input('description');
        $preventDuplicate = $request->boolean('prevent_duplicate');

        $success = $this->rewardService->addPoints($user, $action, $description, $preventDuplicate);

        if ($success) {
            return $this->successResponse([
                'points' => config("rewards.actions.$action", 0),
            ], 'Points awarded successfully');
        }

        return $this->errorResponse('Invalid action or no points awarded', 400);
    }
}
