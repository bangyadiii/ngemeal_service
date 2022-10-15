<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNewUserStoreRequest;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Requests\UpdateStoreRequest;
use App\Traits\MediaUploadTrait;

class StoreController extends Controller
{
    use MediaUploadTrait;
    public static $modelName = "store";

    public function index()
    {
        //
    }

    public function store(CreateNewUserStoreRequest $request)
    {
        $validated = $request->except('logo');
        $validated['user_id'] = $request->user()->id;

        $this->checkAndCreateDirIfNotExist(self::$modelName);
        if ($request->hasFile("logo")) {
            $filePath =  $this->storeMedia($request->file("logo"), self::$modelName + "/logo", true);
            if (!$filePath) {
                return ResponseFormatter::error(
                    "Error occur while creating new store.",
                    500,
                    "Failed to upload images."
                );
            }
            $validated['logo_path'] = $filePath;
        }

        $store = Store::create($validated);
        return ResponseFormatter::success("CREATED", 201, $store);
    }

    public function update(UpdateStoreRequest $request, Store $store)
    {
        $validated = $request->except("logo");
        $validated["user_id"] = $request->user()->id;

        $this->checkAndCreateDirIfNotExist(self::$modelName);
        if ($request->hasFile("logo")) {
            $filePath =  $this->storeMedia($request->file("logo"), self::$modelName + "/logo", true);
            if (!$filePath) {
                return ResponseFormatter::error(
                    "Error occur while updating store.",
                    500,
                    "Failed to upload images."
                );
            }
            $validated['logo_path'] = $filePath;
        }
        $store->updateOrFail($validated);
        return ResponseFormatter::success("Store has been updated.", 200, $store->fresh(['user']));
    }

    public function destroy(Store $store)
    {
        $store->deleteOrFail();
        return ResponseFormatter::success("Store has been deleted.", 200);
    }
}
