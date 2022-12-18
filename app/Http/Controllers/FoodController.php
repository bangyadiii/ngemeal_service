<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\FoodStoreRequest;
use App\Http\Requests\FoodUpdateRequest;
use App\Models\Food;
use Illuminate\Http\Request;
use App\Traits\MediaUploadTrait;
use Illuminate\Support\Facades\Storage;

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
        $storeId = $request->input("store_id");

        $price_from = $request->input("price_from");
        $price_to = $request->input("price_to");

        $rate_from = $request->input("rate_from");
        $rate_to = $request->input("rate_to");
        $category = $request->input("category");

        $view_count_from = null;
        $orderByCol = null;
        $orderByType = null;

        if ($id) {
            $food = Food::with("images", "store")->find($id);

            if (!$food) {
                return ResponseFormatter::error("Food not found", 404);
            }

            $food->view_count++;
            $food->save();

            return ResponseFormatter::success("OK", 200, $food);
        }

        if ($category === "murah" && !$price_to  && !$price_from) {
            $price_to = 25000;
        } elseif ($category === "populer") {
            $rate_from = 4;
            $view_count_from =  2000;
            $orderByCol = "view_count";
            $orderByType = "desc";
        } elseif ($category === "rekomendasi") {
            $rate_from = 4;
            $view_count_from =  2000;
            $price_to = 25000;
            $orderByCol = "rate";
            $orderByType = "desc";
        }

        $food = Food::query();

        if ($name) {
            $food->where("name", "like", "%" . $name . "%");
        }
        if ($storeId) {
            $food->where("store_id", $storeId);
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

        if ($view_count_from) {
            $food->where("view_count", ">=", $view_count_from);
        }

        if ($orderByCol && $orderByType) {
            $food->orderBy($orderByCol, $orderByType);
        }


        return ResponseFormatter::success(
            "Product list berhasil diambil",
            200,
            $food->with(["images" => function ($query) {
                $query->where('is_primary', true);
                return $query;
            }, "store"])->paginate($limit)
        );
    }
    public function store(FoodStoreRequest $request)
    {
        $validatedData = $request->safe()->except("images");
        $food = Food::create($validatedData);

        return ResponseFormatter::success("CREATED", 201, $food->load("images", "store"));
    }

    public function update(FoodUpdateRequest $request, Food $food)
    {
        $data = $request->safe()->except("images");
        $food->forceFill($data)->saveOrFail();
        return ResponseFormatter::success("Food has been updated successfully", 200, $food->load("images", "store"));
    }

    public function destroy(Food $food)
    {
        $food->deleteOrFail();
        if (\count($food->images) > 0) {
            foreach ($food->images as  $image) {
                $this->removeMedia($image->image_path);
            }
        }
        return ResponseFormatter::success("Food has been deleted", 200);
    }

    public function forceDestroy(Food $food)
    {
        try {
            $food->forceDelete();

            if (\count($food->images) > 0) {
                foreach ($food->images as  $image) {
                    $this->removeMedia($image->image_path);
                }
            }
            return ResponseFormatter::success("Food has been deleted", 200);
        } catch (\Throwable $th) {
            return ResponseFormatter::error("Error occur", 500, $th->getMessage());
        }
    }
}
