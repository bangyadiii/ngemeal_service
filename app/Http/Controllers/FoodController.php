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

    public static $modelName = "Food";
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
            $food = Food::with("images")->find($id);

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
            $food->where("name", "like", "%" . $types . "%");
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


        return ResponseFormatter::success("Product list berhasil diambil", 200, $food->with("images")->paginate($limit));
    }
    public function store(FoodStoreRequest $request)
    {
        $validatedData = $request->except("images");
        $food = Food::create($validatedData);
        $imagesArr = array();
        $files = $request->file('images', []);

        $this->checkAndCreateDirIfNotExist(self::$modelName);
        foreach ($files as $key => $file) {
            $isPrimary = $key == 0 ? true : false;
            $imagePath = $this->storeMedia($file, self::$modelName, $isPrimary, $food);

            if (!$imagePath) {
                return ResponseFormatter::error("Occur while uploading photo", 500, $imagesArr);
            }
            $imagesArr[] = $imagePath;
        }
        $food->images()->insert($imagesArr);

        $food =  Food::with("images")->find($food->id);
        return ResponseFormatter::success("CREATED", 201, $food);
    }

    public function update(FoodUpdateRequest $request, Food $food)
    {
        $data = $request->validated();
        $food->fill($data)->save();
        $food = Food::with("images")->find($food->id);
        return ResponseFormatter::success("Food has been updated successfully", 200, $food);
    }

    public function destroy(Food $food)
    {
        $food->delete();
        return ResponseFormatter::success("Food has been deleted", 200);
    }
}
