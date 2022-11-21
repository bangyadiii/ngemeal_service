<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Http\Request;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Requests\UpdatePhotoProfileRequest;
use Exception;

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
        $user->saveOrFail();

        return ResponseFormatter::success(
            "OK",
            200,
            $user
        );
    }

    public function uploadAvatar(UpdatePhotoProfileRequest $request)
    {
        $user = $request->user();

        try {
            $user->updateProfilePhoto($request->photo);
        } catch (Exception $e) {
            return ResponseFormatter::errorStatus(500, $e);
        }

        return  ResponseFormatter::success("OK", 200, $user->fresh());
    }
}
