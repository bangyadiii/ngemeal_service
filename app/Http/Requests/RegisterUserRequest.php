<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            "address" => ["string"],
            "phone_number" => ["string", "min:8", "max:14"],
            "house_number" => ["string", 'max:255'],
            "city" => ["string", 'max:255'],
            'password' => Password::default(),
            "profile_photo" => ["image", "mimes:png,jpg,webp", "max:2098"],
        ];
    }
}
