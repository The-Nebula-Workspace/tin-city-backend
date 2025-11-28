<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class BusTrackingService
{
    private const ACTIVE_BUSES_KEY = 'active_buses';

    private const BUS_LOCATION_PREFIX = 'bus_location:';

    private const ROUTE_BUSES_PREFIX = 'route_buses:';

    private const LOCATION_TTL = 300; // 5 minutes

    /**
     * Store active bus location in Redis
     */
    public function storeActiveBusLocation(int $userId, int $routeId, array $locationData): void
    {
        $busKey = $this->getBusKey($userId, $routeId);
        $timestamp = now()->toIso8601String();

        $data = array_merge($locationData, [
            'user_id' => $userId,
            'route_id' => $routeId,
            'timestamp' => $timestamp,
        ]);

        // Store bus location with TTL
        Redis::setex($busKey, self::LOCATION_TTL, json_encode($data));

        // Add to active buses set
        Redis::sadd(self::ACTIVE_BUSES_KEY, $busKey);

        // Add to route-specific set
        Redis::sadd($this->getRouteBusesKey($routeId), $busKey);

        // Set expiry on route set
        Redis::expire($this->getRouteBusesKey($routeId), self::LOCATION_TTL);
    }

    /**
     * Get all active buses for a route
     */
    public function getActiveBusesForRoute(int $routeId): array
    {
        $routeKey = $this->getRouteBusesKey($routeId);
        $busKeys = Redis::smembers($routeKey);

        $activeBuses = [];
        foreach ($busKeys as $busKey) {
            $data = Redis::get($busKey);
            if ($data) {
                $activeBuses[] = json_decode($data, true);
            } else {
                // Remove expired key from set
                Redis::srem($routeKey, $busKey);
            }
        }

        return $activeBuses;
    }

    /**
     * Get all active buses across all routes
     */
    public function getAllActiveBuses(): array
    {
        $busKeys = Redis::smembers(self::ACTIVE_BUSES_KEY);

        $activeBuses = [];
        foreach ($busKeys as $busKey) {
            $data = Redis::get($busKey);
            if ($data) {
                $activeBuses[] = json_decode($data, true);
            } else {
                // Remove expired key from set
                Redis::srem(self::ACTIVE_BUSES_KEY, $busKey);
            }
        }

        return $activeBuses;
    }

    /**
     * Validate if user movement matches route coordinates
     */
    public function validateUserOnRoute(array $userLocation, array $routeCoordinates, float $threshold = 0.05): bool
    {
        // threshold in km (50 meters default)
        $userLat = $userLocation['latitude'];
        $userLng = $userLocation['longitude'];

        foreach ($routeCoordinates as $point) {
            $distance = $this->calculateDistance(
                $userLat,
                $userLng,
                $point['latitude'],
                $point['longitude']
            );

            if ($distance <= $threshold) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Generate unique bus key
     */
    private function getBusKey(int $userId, int $routeId): string
    {
        return self::BUS_LOCATION_PREFIX."{$routeId}:{$userId}";
    }

    /**
     * Generate route buses key
     */
    private function getRouteBusesKey(int $routeId): string
    {
        return self::ROUTE_BUSES_PREFIX.$routeId;
    }

    /**
     * Remove user from active buses
     */
    public function removeActiveBus(int $userId, int $routeId): void
    {
        $busKey = $this->getBusKey($userId, $routeId);
        Redis::del($busKey);
        Redis::srem(self::ACTIVE_BUSES_KEY, $busKey);
        Redis::srem($this->getRouteBusesKey($routeId), $busKey);
    }
}
