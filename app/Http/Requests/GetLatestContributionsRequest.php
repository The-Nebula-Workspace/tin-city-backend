<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetLatestContributionsRequest extends FormRequest
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
            'type' => 'nullable|in:location,crowding,activity',
            'limit' => 'nullable|integer|min:1|max:100',
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
            'type.in' => 'The type must be one of: location, crowding, or activity.',
            'limit.min' => 'The limit must be at least 1.',
            'limit.max' => 'The limit cannot exceed 100.',
        ];
    }
}

