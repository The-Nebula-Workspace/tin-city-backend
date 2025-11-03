<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // User is updating their own profile
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'dob' => 'sometimes|required|date|before:today',
            'gender' => 'sometimes|required|in:male,female,other',
            'phone' => 'sometimes|required|string|max:20',
            'location' => 'sometimes|required|string|max:255',
            'avatar' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048', // 2MB max
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required when provided.',
            'dob.required' => 'The date of birth field is required when provided.',
            'dob.before' => 'The date of birth must be a date before today.',
            'gender.required' => 'The gender field is required when provided.',
            'gender.in' => 'The selected gender is invalid. Valid values are: male, female, other.',
            'phone.required' => 'The phone number field is required when provided.',
            'location.required' => 'The location field is required when provided.',
            'avatar.image' => 'The avatar must be an image file.',
            'avatar.mimes' => 'The avatar must be a file of type: jpg, jpeg, png.',
            'avatar.max' => 'The avatar may not be greater than 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'dob' => 'date of birth',
        ];
    }

    /**
     * Get the body parameters for the request.
     *
     * @return array
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'The user\'s full name',
                'example' => 'John Doe Updated',
            ],
            'dob' => [
                'description' => 'Date of birth in YYYY-MM-DD format',
                'example' => '1990-01-01',
            ],
            'gender' => [
                'description' => 'User\'s gender',
                'example' => 'male',
            ],
            'phone' => [
                'description' => 'User\'s phone number',
                'example' => '+1234567890',
            ],
            'location' => [
                'description' => 'User\'s location',
                'example' => 'New York, USA',
            ],
        ];
    }
}