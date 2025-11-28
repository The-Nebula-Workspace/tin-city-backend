<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Route;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all routes
        $routes = Route::all();

        if ($routes->isEmpty()) {
            $this->command->error('No routes found! Please run RouteSeeder first.');

            return;
        }

        // Bus naming patterns for Jos Metro
        $busTypes = [
            'JM' => 'Jos Metro',      // Jos Metro buses
            'TB' => 'Terminus-Bukuru', // Route-specific
            'TV' => 'Terminus-Vom',
            'TBL' => 'Terminus-Barkin Ladi',
            'BR' => 'Bukuru-Rayfield',
            'TAR' => 'Terminus-Angwan Rogo',
            'TDK' => 'Terminus-Dadin Kowa',
            'TR' => 'Terminus-Rikkos',
            'BJ' => 'Bukuru-JUTH',
        ];

        $busCount = 0;

        foreach ($routes as $route) {
            // Determine bus prefix based on route
            $prefix = $this->getBusPrefix($route->name);

            // Number of buses per route (varies by route popularity)
            $numberOfBuses = $this->getBusCountForRoute($route->name);

            for ($i = 1; $i <= $numberOfBuses; $i++) {
                $busNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
                $busName = "{$prefix}-{$busNumber}";

                Bus::create([
                    'route_id' => $route->id,
                    'name' => $busName,
                ]);

                $busCount++;
            }

            $this->command->info("Created {$numberOfBuses} buses for route: {$route->name}");
        }

        $this->command->info("Bus seeding completed! Total buses created: {$busCount}");
    }

    /**
     * Get bus prefix based on route name
     */
    private function getBusPrefix(string $routeName): string
    {
        return match (true) {
            str_contains($routeName, 'Terminus to Bukuru') => 'TB',
            str_contains($routeName, 'Terminus to Vom') => 'TV',
            str_contains($routeName, 'Terminus to Barkin Ladi') => 'TBL',
            str_contains($routeName, 'Bukuru to Rayfield') => 'BR',
            str_contains($routeName, 'Terminus to Angwan Rogo') => 'TAR',
            str_contains($routeName, 'Terminus to Dadin Kowa') => 'TDK',
            str_contains($routeName, 'Terminus to Rikkos') => 'TR',
            str_contains($routeName, 'JUTH') => 'BJ',
            default => 'JM',
        };
    }

    /**
     * Get number of buses for each route based on popularity/distance
     */
    private function getBusCountForRoute(string $routeName): int
    {
        return match (true) {
            // Major routes get more buses
            str_contains($routeName, 'Terminus to Bukuru') => 8,
            str_contains($routeName, 'Terminus to Vom') => 6,
            str_contains($routeName, 'Terminus to Barkin Ladi') => 5,

            // Medium routes
            str_contains($routeName, 'Bukuru to Rayfield') => 4,
            str_contains($routeName, 'JUTH') => 5,

            // Shorter routes
            str_contains($routeName, 'Terminus to Angwan Rogo') => 4,
            str_contains($routeName, 'Terminus to Dadin Kowa') => 4,
            str_contains($routeName, 'Terminus to Rikkos') => 3,

            default => 3,
        };
    }
}
