<?php

namespace App\Http\Requests;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ApiLoginRequest extends FormRequest
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
            "email" => ["required", "email"],
            "password" => ["required", "string"],
            "device_name" => ["string"]
        ];
    }

    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        if (!Auth::attempt($this->only(["email", "password"]), $this->boolean("remember"))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                "email" => __('auth.failed')
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    private function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            //
            return;
        }

        \event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
    //
    // @return string
    private function throttleKey()
    {
        return Str::lower($this->input("email") . '|' . $this->ip());
        //
    }
}
