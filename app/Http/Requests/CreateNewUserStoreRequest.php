<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateNewUserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        request()->user() && request()->user()->store();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "store_name" => ['required', 'string'],
            "address" => ['required', 'string'],
            "description" => ['optional', 'string'],
            "rekening_number" => ['optional', 'numeric'],
            "logo" => ['optional', 'file', "mimes:svg,png,jpg,jpeg"],
        ];
    }
}
