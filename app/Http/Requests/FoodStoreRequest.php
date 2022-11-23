<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoodStoreRequest extends FormRequest
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
            "name" => ["required", "string"],
            "description" => ["required", "string"],
            "ingredients" => ["string"],
            "price" => ["required", "numeric"],
            "rate" => ["numeric", "min:1", "max:5"],
            "types" => ["string"],
            "images" => ["file", "mimes:png,jpg,jpeg", "max:2096"]
        ];
    }
}
