<?php

namespace App\Http\Controllers\Auth;

use App\Events\NewUserRegistered;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    public function store(RegisterUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        NewUserRegistered::dispatch($user);

        return ResponseFormatter::success("OK", 200, $user);
    }
}
