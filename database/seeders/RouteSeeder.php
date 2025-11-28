<?php

namespace Database\Seeders;

use App\Models\Route;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = [
            [
                'name' => 'Terminus to Bukuru',
                'start_point' => 'Terminus',
                'end_point' => 'Bukuru',
                'encoded_polyline' => 'mfp_Ijk~hEo}@yDa@eBk@sCm@wCq@eDu@gDy@iE}@kE',
                'distance_km' => 8.5,
                'stops' => [
                    ['name' => 'Terminus', 'latitude' => 9.8965, 'longitude' => 8.8583, 'order_index' => 1],
                    ['name' => 'Ahmadu Bello Way', 'latitude' => 9.9012, 'longitude' => 8.8621, 'order_index' => 2],
                    ['name' => 'Jos Main Market', 'latitude' => 9.9145, 'longitude' => 8.8734, 'order_index' => 3],
                    ['name' => 'Plateau Specialist Hospital', 'latitude' => 9.9234, 'longitude' => 8.8812, 'order_index' => 4],
                    ['name' => 'Rayfield', 'latitude' => 9.8876, 'longitude' => 8.8945, 'order_index' => 5],
                    ['name' => 'Lamingo', 'latitude' => 9.8654, 'longitude' => 8.9123, 'order_index' => 6],
                    ['name' => 'Bukuru Motor Park', 'latitude' => 9.7965, 'longitude' => 8.8683, 'order_index' => 7],
                ],
            ],
            [
                'name' => 'Terminus to Vom',
                'start_point' => 'Terminus',
                'end_point' => 'Vom',
                'encoded_polyline' => 'ngp_Ijk~hEp}@xDa@dBk@rCm@vCq@zDu@fD',
                'distance_km' => 12.3,
                'stops' => [
                    ['name' => 'Terminus', 'latitude' => 9.8965, 'longitude' => 8.8583, 'order_index' => 1],
                    ['name' => 'Secretariat Junction', 'latitude' => 9.9087, 'longitude' => 8.8698, 'order_index' => 2],
                    ['name' => 'Zaria Road', 'latitude' => 9.9234, 'longitude' => 8.8856, 'order_index' => 3],
                    ['name' => 'Gangare', 'latitude' => 9.8543, 'longitude' => 8.8234, 'order_index' => 4],
                    ['name' => 'Vom Junction', 'latitude' => 9.7234, 'longitude' => 8.7856, 'order_index' => 5],
                    ['name' => 'Vom Town', 'latitude' => 9.7123, 'longitude' => 8.7734, 'order_index' => 6],
                ],
            ],
            [
                'name' => 'Terminus to Barkin Ladi',
                'start_point' => 'Terminus',
                'end_point' => 'Barkin Ladi',
                'encoded_polyline' => 'ohp_Ijk~hEq}@zDc@fBm@tCo@xCr@|Du@hD',
                'distance_km' => 15.7,
                'stops' => [
                    ['name' => 'Terminus', 'latitude' => 9.8965, 'longitude' => 8.8583, 'order_index' => 1],
                    ['name' => 'Bauchi Road', 'latitude' => 9.9156, 'longitude' => 8.8712, 'order_index' => 2],
                    ['name' => 'Hwolshe', 'latitude' => 9.9876, 'longitude' => 8.9234, 'order_index' => 3],
                    ['name' => 'Kuru', 'latitude' => 10.0234, 'longitude' => 8.9567, 'order_index' => 4],
                    ['name' => 'Barkin Ladi Junction', 'latitude' => 9.5234, 'longitude' => 8.8912, 'order_index' => 5],
                    ['name' => 'Barkin Ladi Town', 'latitude' => 9.5123, 'longitude' => 8.8834, 'order_index' => 6],
                ],
            ],
            [
                'name' => 'Bukuru to Rayfield',
                'start_point' => 'Bukuru',
                'end_point' => 'Rayfield',
                'encoded_polyline' => 'mep_Ihk~hEn}@wDa@cBk@qCm@uCq@yD',
                'distance_km' => 5.2,
                'stops' => [
                    ['name' => 'Bukuru Motor Park', 'latitude' => 9.7965, 'longitude' => 8.8683, 'order_index' => 1],
                    ['name' => 'Gyel', 'latitude' => 9.8234, 'longitude' => 8.8756, 'order_index' => 2],
                    ['name' => 'Gada Biu', 'latitude' => 9.8456, 'longitude' => 8.8823, 'order_index' => 3],
                    ['name' => 'Zawan', 'latitude' => 9.8678, 'longitude' => 8.8889, 'order_index' => 4],
                    ['name' => 'Rayfield', 'latitude' => 9.8876, 'longitude' => 8.8945, 'order_index' => 5],
                ],
            ],
            [
                'name' => 'Terminus to Angwan Rogo',
                'start_point' => 'Terminus',
                'end_point' => 'Angwan Rogo',
                'encoded_polyline' => 'mfp_Ijk~hEo}@yDa@eBk@sCm@wCq@eD',
                'distance_km' => 6.8,
                'stops' => [
                    ['name' => 'Terminus', 'latitude' => 9.8965, 'longitude' => 8.8583, 'order_index' => 1],
                    ['name' => 'Murtala Mohammed Way', 'latitude' => 9.9023, 'longitude' => 8.8645, 'order_index' => 2],
                    ['name' => 'Farin Gada', 'latitude' => 9.9134, 'longitude' => 8.8723, 'order_index' => 3],
                    ['name' => 'Tudun Wada', 'latitude' => 9.9245, 'longitude' => 8.8801, 'order_index' => 4],
                    ['name' => 'Angwan Rogo', 'latitude' => 9.9356, 'longitude' => 8.8879, 'order_index' => 5],
                ],
            ],
            [
                'name' => 'Terminus to Dadin Kowa',
                'start_point' => 'Terminus',
                'end_point' => 'Dadin Kowa',
                'encoded_polyline' => 'mfp_Ijk~hEo}@yDa@eBk@sCm@wCq@eDu@gD',
                'distance_km' => 7.4,
                'stops' => [
                    ['name' => 'Terminus', 'latitude' => 9.8965, 'longitude' => 8.8583, 'order_index' => 1],
                    ['name' => 'Polo Roundabout', 'latitude' => 9.9078, 'longitude' => 8.8656, 'order_index' => 2],
                    ['name' => 'Jenta Adamu', 'latitude' => 9.9189, 'longitude' => 8.8734, 'order_index' => 3],
                    ['name' => 'Jenta Mangoro', 'latitude' => 9.9301, 'longitude' => 8.8812, 'order_index' => 4],
                    ['name' => 'Dadin Kowa', 'latitude' => 9.9412, 'longitude' => 8.8890, 'order_index' => 5],
                ],
            ],
            [
                'name' => 'Terminus to Rikkos',
                'start_point' => 'Terminus',
                'end_point' => 'Rikkos',
                'encoded_polyline' => 'mfp_Ijk~hEo}@yDa@eBk@sC',
                'distance_km' => 4.2,
                'stops' => [
                    ['name' => 'Terminus', 'latitude' => 9.8965, 'longitude' => 8.8583, 'order_index' => 1],
                    ['name' => 'Bank Road', 'latitude' => 9.9034, 'longitude' => 8.8634, 'order_index' => 2],
                    ['name' => 'Ahmadu Bello Stadium', 'latitude' => 9.9123, 'longitude' => 8.8701, 'order_index' => 3],
                    ['name' => 'Rikkos', 'latitude' => 9.9212, 'longitude' => 8.8768, 'order_index' => 4],
                ],
            ],
            [
                'name' => 'Bukuru to Jos University Teaching Hospital',
                'start_point' => 'Bukuru',
                'end_point' => 'JUTH',
                'encoded_polyline' => 'mep_Ihk~hEn}@wDa@cBk@qCm@uC',
                'distance_km' => 9.1,
                'stops' => [
                    ['name' => 'Bukuru Motor Park', 'latitude' => 9.7965, 'longitude' => 8.8683, 'order_index' => 1],
                    ['name' => 'Gyel Junction', 'latitude' => 9.8234, 'longitude' => 8.8756, 'order_index' => 2],
                    ['name' => 'Lamingo Junction', 'latitude' => 9.8654, 'longitude' => 8.9123, 'order_index' => 3],
                    ['name' => 'Secretariat', 'latitude' => 9.9087, 'longitude' => 8.8698, 'order_index' => 4],
                    ['name' => 'JUTH', 'latitude' => 9.8734, 'longitude' => 8.8912, 'order_index' => 5],
                ],
            ],
        ];

        foreach ($routes as $routeData) {
            $stops = $routeData['stops'];
            unset($routeData['stops']);

            $route = Route::create($routeData);

            foreach ($stops as $stopData) {
                $route->stops()->create($stopData);
            }

            $this->command->info("Created route: {$route->name} with ".count($stops).' stops');
        }

        $this->command->info('Route seeding completed successfully!');
    }
}
