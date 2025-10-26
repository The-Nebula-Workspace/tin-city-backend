<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBadgeRouteRequest;
use App\Http\Requests\UpdateBadgeRouteRequest;
use App\Http\Resources\BadgeResource;
use App\Models\Badge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;


/**
 * @group Badge Management
 * APIs for managing and assigning badge .
 * These endpoints handle badge CRUD functionalities.
 * Base URL: `/api/v1/badges`
 */
class BadgeController extends Controller
{
    /**
     * Display a listing of the badges.
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "John Doe",
     *       "description": "I'm a fan of this innovation"
     *       "created_at": "2025-10-18T12:00:00Z"
     *     }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        $badges = Badge::with('userBadges')->get();
        return response()->json(
            [
                'message' => 'All badges retrieved successfully',
                'badges' => BadgeResource::collection($badges),
            ],
            Response::HTTP_OK
        );
    }

    /**
     * 
     * @throws ValidationException
     * Store a newly created badge in storage.
     * @authenticated
     *
     * @bodyParam name string required The name of the badge. Example: John Doe
     *@bodyParam description string required The description of the badge Example: I'm a fan 
     
     *@bodyParam points_required required integer The points_required of the badge assigned Example : 100points
     *
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *   "description": "I'm a fan",
     * "points_required": "100points"
     *     "created_at": "2025-10-18T12:00:00Z"
     *   }
     * }
     */
    public function store(StoreBadgeRouteRequest $request): JsonResponse

    {
        $badge = Badge::create($request->validated());
        return response()->json(
            [
                'message' => 'Badge created successfully',
                'badge' => new BadgeResource($badge),
            ],
            Response::HTTP_OK
        );
    }


    /**
     * Display the specified badge.
     * @urlParam id integer required The ID of the badge. Example: 1
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "description": "I'm a fan",
     *     "points_required": 80points,
     * "icon": "Bronze"
     *     "created_at": "2025-10-18T12:00:00Z"
     *   }
     * }
     * @response 404 scenario="Not Found" {"error": "Badge not found"}
     */
    public function show($id): JsonResponse
    {
        $badge = Badge::where('id', $id)->first();

        if (!$badge) {
            return response()->json(
                [
                    'message' => 'Badge not found',
                    Response::HTTP_NOT_FOUND
                ]
            );
        }

        return response()->json(
            [
                'message' => 'Badge retrieved successfully',
                'badge' => new BadgeResource($badge),
            ],
            Response::HTTP_OK
        );
    }

    /** 
     * @throws ValidationException
     * Update the specified badge in storage.
     * @authenticated
     * @urlParam route integer required The ID of the badge to update. Example: 1
     * @bodyParam name string optional The updated name of the badge.
     * @bodyParam description optional The description updated
     * @response 200 scenario="Updated" {
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe (Updated)",
     *     "description": "lorem ipsum",
     *     
     *   }
     * }
     * @response 403 scenario="Forbidden" {"error": "You are not authorized to perform this action"}
     */
    public function update(UpdateBadgeRouteRequest $request, Badge $badge): JsonResponse
    {
        $badge->update($request->validated());
        return response()->json(
            [
                'message' => 'Badge updated successfully',
                'badge' => new BadgeResource($badge),
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified badge from storage.
     * @authenticated
     * @urlParam route integer required The ID of the badge to delete. Example: 1
     * @response 200 scenario="Deleted" {"message": "badge deleted successfully"}
     * @response 404 scenario="Not Found" {"error": "badge not found"}
     */
    public function destroy(Badge $badge): JsonResponse
    {
        if (!$badge) {
            return response()->json(['error' => 'Badge not found'], Response::HTTP_NOT_FOUND);
        }

        $badge->delete();
        return response()->json(
            ['message' => 'Badge deleted successfully'],
            Response::HTTP_OK
        );
    }
}
