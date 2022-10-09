<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Http\Request;

class UserInformationController extends Controller
{
    public function show(Request $request)
    {
        return ResponseFormatter::success("OK", 200, [
            "user" => $request->user()
        ]);
    }

    public function update(Updateuserprofilerequest $request)
    {
        $user = $request->user();
        $user->fill($request->all());
        $user->save();

        return ResponseFormatter::success(
            "OK",
            200,
            $user
        );
    }
}
