<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutProductRequest extends FormRequest
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
            "user_id" => ["required", "exists:users,id"],
            "food_id" => ["required", "exists:food,id"],
            "quantity" => ["required", "numeric", "min:1"],
            "total" => ["required", "numeric", "min:1"],
            "status" => ["string", "in:failed,pending,success"]
        ];
    }
}
