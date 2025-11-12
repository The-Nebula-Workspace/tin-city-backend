<?php

namespace App\Services;

use App\Models\Route;
use Illuminate\Support\Facades\DB;

class RouteService
{
    /**
     * Retrieve all routes with their related stops and contributions.
     *
     * @return \Illuminate\Database\Eloquent\Collection<Route>
     */
    public function getAllRoutes()
    {
        return Route::with('stops', 'contributions')->orderBy('name')->get();
    }


    /**
     * Find a specific route by its ID.
     *
     * @param int $id
     * @return \App\Models\Route
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int $id): ?Route
    {
        return Route::with('stops', 'contributions')->findOrFail($id);
    }


    /**
     * Create a new route and optionally its stops.
     *
     * @param array $data
     * @return \App\Models\Route
     */
    public function create(array $data): Route
    {
        return DB::transaction(function () use ($data) {
            $route = Route::create($data);

            if (isset($data['stops'])) {
                $route->stops()->createMany($data['stops']);
            }

            return $route->load('stops');
        });
    }


    /**
     * Update an existing route and its stops.
     *
     * @param \App\Models\Route $route
     * @param array $data
     * @return \App\Models\Route
     */
    public function update(Route $route, array $data): Route
    {
        return DB::transaction(function () use ($route, $data) {
            $route->update($data);

            if (isset($data['stops'])) {
                $route->stops()->delete();
                $route->stops()->createMany($data['stops']);
            }

            return $route->load('stops');
        });
    }


    /**
     * Delete a route.
     *
     * @param \App\Models\Route $route
     * @return void
     */
    public function delete(Route $route): void
    {
        $route->delete();
    }
}
