<?php

namespace App\Http\Controllers\Auth;

use App\Events\NewUserRegistered;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    public function store(RegisterUserRequest $request)
    {
        $validated = $request->validated();
        $validated["password"] = Hash::make($validated["password"]);
        $user = new User($validated);
        $user->roles()
            ->associate(Role::where("slug", "customer")->first())
            ->saveOrFail();


        $roles = $user->roles->slug;
        $accessToken = $user->createToken($request->header("user-agent"), [$roles])->plainTextToken;

        NewUserRegistered::dispatch($user);

        return ResponseFormatter::success("OK", 200, [
            "access_token" => $accessToken,
            "token_type" => "Bearer",
            "user" => $user->load('roles:slug')
        ]);
    }
}
