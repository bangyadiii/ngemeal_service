<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function all(Request $request)
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
            $food = Food::find($id);

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


        return ResponseFormatter::success("Product list berhasil diambil", 200, $food->paginate($limit));
    }
}
