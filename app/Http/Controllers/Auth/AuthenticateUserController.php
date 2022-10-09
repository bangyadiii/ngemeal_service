<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuthenticateUserController extends Controller
{
    public function login(ApiLoginRequest $request)
    {
        $user = User::where("email", $request->email)->firstOrFail();

        if (!Auth::attempt($request->only(["email", "password"]))) {
            return ResponseFormatter::error("Unautorized", 401, "Authorization failed");
        }

        $accessToken = $user->createToken($request->device_name)->plainTextToken;

        return ResponseFormatter::success("Authorization success.", 200, [
            "access_token" => $accessToken,
            "token_type" => "Bearer",
            "user" => $user
        ]);
    }


    public function logout(Request $request)
    {
        // delete token
        $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success("No Content", 204);
    }
}
