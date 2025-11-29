<?php

namespace App\Http\Controllers\Badge;

use App\Http\Controllers\Controller;
use App\Http\Requests\Badge\StoreBadgeRequest;
use App\Http\Requests\Badge\UpdateBadgeRequest;
use App\Http\Resources\BadgeResource;
use App\Models\Badge;
use Illuminate\Http\JsonResponse;
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
     * Display a listing of the available badges.
     *
     * @response 200 scenario="Success" {
     *      "success": true,
     *      "message": "Badges retrieved successfully",
     *      "data": [
     *      {
     *          "id": 4,
     *          "name": "Platinum contributor",
     *          "description": "100 contributions",
     *          "points_required": 100,
     *          "icon": null,
     *          "created_at": "2025-11-29T13:57:30.000000Z",
     *          "updated_at": "2025-11-29T13:57:30.000000Z",
     *          "user_badges": []
     *      }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        try {
            $badges = Badge::with('userBadges')->get();

            return $this->successResponse(
                BadgeResource::collection($badges),
                'Badges retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve badges.', 500, $e->getMessage());
        }
    }

    /**
     * Store a newly created badge in storage.
     *
     * @authenticated
     *
     * @bodyParam name string required The name of the badge. Example: Diamond Contributor
     * @bodyParam description string required the description of the badge Example: Contributed to Tin City Metro 200 times.
     * @bodyParam points_required required integer required The Points_required of the badge to be assigned. Example: 100
     * @bodyParam icons nullable string The icon should be assigned if the points_required condtion is met Example: https://example.com/badge-icon.png
     *
     * @response 200 scenario="Success" {
     *     "success": true,
     *     "message": "Badge created successfully",
     *     "data": {
     *         "id": 4,
     *         "name": "Platinum contributor",
     *         "description": "100 contributions",
     *         "points_required": 100,
     *         "icon": null,
     *         "created_at": "2025-11-29T13:57:30.000000Z",
     *         "updated_at": "2025-11-29T13:57:30.000000Z"
     *     }
     *}
     * @response 422 scenario="Validation Error" {
     *     "success": false,
     *     "message": "Validation error.",
     *     "errors": {
     *         "name": ["The name field is required."]
     *     }
     *}
     */
    public function store(StoreBadgeRequest $request): JsonResponse
    {
        try {
            $badge = Badge::create($request->validated());

            return $this->successResponse(
                new BadgeResource($badge),
                'Badge created successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create badge.', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified badge.
     *
     * @urlParam badge integer required The ID of the badge. Example: 1
     *
     * @response 200 scenario="Success" {
     *     "success": true,
     *     "message": "Badge retrieved successfully",
     *     "data": {
     *         "id": 4,
     *         "name": "Platinum contributor",
     *         "description": "100 contributions",
     *         "points_required": 100,
     *         "icon": null,
     *         "created_at": "2025-11-29T13:57:30.000000Z",
     *         "updated_at": "2025-11-29T13:57:30.000000Z"
     *     }
     *}
     * @response 404 scenario="Not Found" {
     *     "success": false,
     *     "message": "Badge not found",
     *     "errors": null
     *}
     */
    public function show(Badge $badge): JsonResponse
    {
        try {
            return $this->successResponse(
                new BadgeResource($badge),
                'Badge retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve badge.', 500, $e->getMessage());
        }
    }

    /**
     * @throws ValidationException
     *                             Update the specified badge in storage.
     *
     * @authenticated
     *
     * @bodyParam name string required The name of the badge. Example: Diamond Contributor
     * @bodyParam description string required the description of the badge Example: Contributed to Tin City Metro 200 times.
     * @bodyParam points_required required integer required The Points_required of the badge to be assigned. Example: 200
     * @bodyParam icons nullable string The icon should be assigned if the points_required condtion is met Example: https://example.com/badge-icon.png
     *
     * @response 200 scenario="Updated" {
     *     "success": true,
     *     "message": "Badge updated successfully",
     *     "data": {
     *         "id": 4,
     *         "name": "Platinum contributor",
     *         "description": "100 contributions",
     *         "points_required": 100,
     *         "icon": null,
     *         "created_at": "2025-11-29T13:57:30.000000Z",
     *         "updated_at": "2025-11-29T13:57:30.000000Z"
     *     }
     *}
     * @response 422 scenario="Validation Error" {
     *     "success": false,
     *     "message": "Validation error.",
     *     "errors": {
     *         "name": ["The name field is required."]
     *     }
     *}
     * @response 404 scenario="Not Found" {
     *     "success": false,
     *     "message": "Badge not found",
     *     "errors": null
     *}
     */
    public function update(UpdateBadgeRequest $request, Badge $badge): JsonResponse
    {
        try {
            $badge->update($request->validated());

            return $this->successResponse(
                new BadgeResource($badge),
                'Badge updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update badge.', 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified badge from storage.
     *
     * @authenticated
     *
     * @urlParam badge integer required The ID of the badge to delete. Example: 1
     *
     * @response 200 scenario="Deleted" {
     *     "success": true,
     *     "message": "Badge deleted successfully",
     *}
     * @response 404 scenario="Not Found" {
     *     "success": false,
     *     "message": "Badge not found",
     *     "errors": null
     *}
     */
    public function destroy(Badge $badge): JsonResponse
    {
        try {
            $badge->delete();

            return $this->successResponse(null, 'Badge deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete badge.', 500, $e->getMessage());
        }

        return $this->successResponse(null, 'Badge deleted successfully');
    }
}
