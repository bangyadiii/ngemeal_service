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

    public function index(Request $request)
    {
        $id = $request->store_id;
        $store_name = $request->store_name;
        $limit = \intval($request->limit) || 20;

        if ($id) {
            $store = Store::with('users', 'food')->find($id);

            if (!$store) {
                return ResponseFormatter::error("NOT FOUND", 404, "Store not found.");
            }
            return ResponseFormatter::success("Getting store data successfully.", 200, $store);
        }
        $store = Store::query();
        if ($store_name) {
            $store->where('store_name', "LIKE", "%" . $store_name . "%");
        }
        $store = $store->with("user", "food")->paginate($limit);

        return ResponseFormatter::success(
            "Getting store data list successfully.",
            200,
            $store
        );
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
