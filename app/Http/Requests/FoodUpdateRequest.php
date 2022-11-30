<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoodUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can("update", $this->food);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => ["string"],
            "description" => ["string"],
            "ingredients" => ["string"],
            "price" => ["numeric"],
            "rate" => ["numeric", "min:1", "max:5"],
            "types" => ["string"],
        ];
    }
}
