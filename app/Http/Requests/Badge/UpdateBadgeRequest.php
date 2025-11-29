<?php

namespace App\Http\Requests\Badge;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBadgeRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('badges')->ignore($this->badge->id)],
            'description' => ['sometimes', 'string'],
            'points_required' => ['sometimes', 'integer', 'min:0'],
            'icon' => ['nullable', 'string', Rule::unique('badges')->ignore($this->badge->id)],
        ];
    }
}
