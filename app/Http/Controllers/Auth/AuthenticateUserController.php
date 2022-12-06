<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AuthenticateUserController extends Controller
{
    public function login(ApiLoginRequest $request)
    {
        $user = User::with("roles:slug")->where("email", $request->email)->first();

        if (!$user) {
            return ResponseFormatter::error(
                "Email doesn't exist in our records.",
                400,
            );
        }
        if (!Hash::check($request->password, $user->password)) {
            return ResponseFormatter::error(
                "The credentials are incorrect.",
                400,
            );
        }

        // delete previous token 
        $user->tokens()->where("name", $request->device_name)->delete();

        $roles = $user->roles()->pluck("slug")->toArray();

        $device = $request->header("user-agent");
        $accessToken = $user->createToken($device, $roles)->plainTextToken;

        return ResponseFormatter::success("Authorization success.", 200, [
            "access_token" => $accessToken,
            "token_type" => "Bearer",
            "user" => $user
        ]);
    }


    public function logout(Request $request)
    {
        // delete token
        // $request->user()->currentAccessToken()->delete();

        $result = Auth::user()->currentAccessToken()->delete();
        \abort_if(!$result, 400, "Logout failed");

        return ResponseFormatter::success("No Content", 204);
    }
}
