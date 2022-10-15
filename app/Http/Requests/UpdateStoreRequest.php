<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        request()->user()->tokenCan("store:update");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "store_name" => ['optional', 'string'],
            "address" => ['optional', 'string'],
            "address" => ['optional', 'string'],
            "rekening_number" => ['optional', 'numeric'],
            "logo" => ['optional', 'file', "mimes:png,jpg,jpeg,svg"],
        ];
    }
}
