<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CreateNewUserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can("create", App\Models\Store::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "store_name" => ['required', 'string', 'max:255'],
            "address" => ['required', 'string', 'max:255'],
            "description" => ['string', 'max:255'],
            "rekening_number" => ['numeric'],
            "logo" => ['file', "mimes:svg,png,jpg,jpeg", 'max:2098'],
        ];
    }
}
