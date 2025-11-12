<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBadgeRouteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
       
        return true;
    }

     /**
     * Define the validation rules for storing a route.
     *
     * @bodyParam name string required The name of the badge. Example: John Doe
     * @bodyParam description string required the description of the badge Example: I'am die hard fan of metrobus
     * @bodyParam points_required required integer required The Points_required of the badge to be assigned. Example: 100points
     *@bodyParam icons nullable string The icon should be assigned if the points_required condtion is met Example: Gold Avatar
     */

  
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'points_required' => ['required', 'integer', 'min:0'],
            'icon' => ['nullabe', 'string'],
        ];
    }
}
