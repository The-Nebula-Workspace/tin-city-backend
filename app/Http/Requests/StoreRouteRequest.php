<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization handled via middleware (e.g., admin-only access)
        return true;
    }

    /**
     * Define the validation rules for storing a route.
     *
     * @bodyParam name string required The name of the route. Example: Rayfield - Terminus
     * @bodyParam start_point string required The starting location of the route. Example: Rayfield
     * @bodyParam end_point string required The ending location of the route. Example: Terminus
     * @bodyParam encoded_polyline string required The encoded polyline string representing the route path.
     * @bodyParam distance_km numeric required The total route distance in kilometers. Example: 7.5
     * @bodyParam stops array optional List of route stops, each with name, coordinates, and order index.
     * @bodyParam stops[].name string required The stop name. Example: Tudun Wada
     * @bodyParam stops[].latitude numeric required The latitude of the stop. Example: 9.87345
     * @bodyParam stops[].longitude numeric required The longitude of the stop. Example: 8.89123
     * @bodyParam stops[].order_index integer required The stop’s sequence order on the route. Example: 1
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'start_point' => ['required', 'string', 'max:255'],
            'end_point' => ['required', 'string', 'max:255'],
            'encoded_polyline' => ['required', 'string'],
            'distance_km' => ['required', 'numeric', 'min:0'],
            'stops' => ['sometimes', 'array'],
            'stops.*.name' => ['required_with:stops', 'string'],
            'stops.*.latitude' => ['required_with:stops', 'numeric'],
            'stops.*.longitude' => ['required_with:stops', 'numeric'],
            'stops.*.order_index' => ['required_with:stops', 'integer'],
        ];
    }
}
