<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\FoodStoreRequest;
use App\Http\Requests\FoodUpdateRequest;
use App\Models\Food;
use Illuminate\Http\Request;
use App\Traits\MediaUploadTrait;

class FoodController extends Controller
{
    use MediaUploadTrait;

    public static $modelName = "food";
    public function index(Request $request)
    {
        $id = $request->input("food_id");
        $name = $request->input("name");
        $limit = $request->input("limit", 6);
        $types = $request->input("types");

        $price_from = $request->input("price_from");
        $price_to = $request->input("price_to");

        $rate_from = $request->input("rate_from");
        $rate_to = $request->input("rate_to");


        if ($id) {
            $food = Food::with("images", "store")->find($id);

            if ($food) {
                return ResponseFormatter::error("Food not found", 404);
            }
            return ResponseFormatter::success("OK", 200, $food);
        }


        $food = Food::query();

        if ($name) {
            $food->where("name", "like", "%" . $name . "%");
        }
        if ($types) {
            $food->where("types", "like", "%" . $types . "%");
        }
        if ($price_from) {
            $food->where("price", ">=", $price_from);
        }
        if ($price_to) {
            $food->where("price", "<=", $price_to);
        }
        if ($rate_from) {
            $food->where("rate", ">=", $rate_from);
        }
        if ($rate_to) {
            $food->where("rate", "<=", $rate_to);
        }


        return ResponseFormatter::success(
            "Product list berhasil diambil",
            200,
            $food->with("images", 'store')->paginate($limit)
        );
    }
    public function store(FoodStoreRequest $request)
    {
        $validatedData = $request->safe()->except("images");
        $food = Food::create($validatedData);
        $imagesArr = array();
        $this->checkAndCreateDirIfNotExist(self::$modelName);

        $path = $this->storeMedia($request->images, self::$modelName);

        if (!$path) {
            return ResponseFormatter::error("Occur while uploading photo", 500, $imagesArr);
        }
        $imagePath['image_path'] = asset("storage/$path");
        $imagePath['is_primary'] = 1;
        $imagesArr[] = $imagePath;

        $food->images()->createMany($imagesArr);

        return ResponseFormatter::success("CREATED", 201, $food->load("images", "store"));
    }

    public function update(FoodUpdateRequest $request, Food $food)
    {
        $data = $request->safe()->except('images');
        $food->fill($data)->save();
        return ResponseFormatter::success("Food has been updated successfully", 200, $food->load("images", "store"));
    }

    public function destroy(Food $food)
    {
        $food->deleteOrFail();

        return ResponseFormatter::success("Food has been deleted", 200);
    }

    public function forceDestroy(Food $food)
    {
        try {
            $food->forceDelete();

            if (\count($food->images) > 0) {
                foreach ($food->images as  $image) {
                    $this->removeMedia(\str_replace(\asset(""), "", $image->image_path));
                }
            }
            return ResponseFormatter::success("Food has been deleted", 200);
        } catch (\Throwable $th) {
            return ResponseFormatter::error("Error occur", 500, $th->getMessage());
        }
    }
}
