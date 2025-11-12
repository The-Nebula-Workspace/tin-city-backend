<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRouteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define the validation rules for updating a route.
     *
     * @bodyParam name string optional The updated route name. Example: Rayfield - Jos South
     * @bodyParam start_point string optional Updated starting point. Example: Rayfield
     * @bodyParam end_point string optional Updated destination. Example: Jos South
     * @bodyParam encoded_polyline string optional Updated encoded polyline for the new route path.
     * @bodyParam distance_km numeric optional Updated total distance in kilometers. Example: 8.2
     * @bodyParam stops array optional Updated list of route stops.
     * @bodyParam stops[].name string required The stop name. Example: British-America
     * @bodyParam stops[].latitude numeric required The latitude of the stop. Example: 9.87231
     * @bodyParam stops[].longitude numeric required The longitude of the stop. Example: 8.91120
     * @bodyParam stops[].order_index integer required The new order index of the stop. Example: 2
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'start_point' => ['sometimes', 'string', 'max:255'],
            'end_point' => ['sometimes', 'string', 'max:255'],
            'encoded_polyline' => ['sometimes', 'string'],
            'distance_km' => ['sometimes', 'numeric', 'min:0'],
            'stops' => ['sometimes', 'array'],
            'stops.*.name' => ['required_with:stops', 'string'],
            'stops.*.latitude' => ['required_with:stops', 'numeric'],
            'stops.*.longitude' => ['required_with:stops', 'numeric'],
            'stops.*.order_index' => ['required_with:stops', 'integer'],
        ];
    }
}
