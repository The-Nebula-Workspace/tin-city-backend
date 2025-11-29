<?php

namespace App\Http\Requests\Badge;

use Illuminate\Foundation\Http\FormRequest;

class StoreBadgeRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:badges'],
            'description' => ['required', 'string'],
            'points_required' => ['required', 'integer', 'min:0'],
            'icon' => ['nullable', 'string', 'unique:badges'],
        ];
    }
}
