<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitCrowdingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'route_id' => 'required|exists:routes,id',
            'status' => 'required|in:full,standing,seats_available',
            'crowding_level' => 'required|integer|between:1,5',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'route_id.required' => 'The route ID is required.',
            'route_id.exists' => 'The selected route does not exist.',
            'status.required' => 'The bus status is required.',
            'status.in' => 'The status must be one of: full, standing, or seats_available.',
            'crowding_level.required' => 'The crowding level is required.',
            'crowding_level.between' => 'The crowding level must be between 1 and 5.',
            'latitude.required' => 'The latitude is required.',
            'latitude.between' => 'The latitude must be between -90 and 90.',
            'longitude.required' => 'The longitude is required.',
            'longitude.between' => 'The longitude must be between -180 and 180.',
        ];
    }
}
