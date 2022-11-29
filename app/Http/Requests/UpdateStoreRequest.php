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
        \info($this->store);
        return $this->user()->can("update", $this->store);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "store_name" => ['string'],
            "address" => ['string'],
            "address" => ['string'],
            "rekening_number" => ['numeric'],
            "logo" => ['file', "mimes:png,jpg,jpeg,svg"],
        ];
    }
}
