<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique('users')],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'phone_number' => ['nullable', 'string', 'min:8', 'max:15'],
            'city' => ['nullable', 'string'],
        ];
    }
}
