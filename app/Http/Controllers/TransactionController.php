<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input("food_id");
        $food_id = $request->input("food_id");
        $status = $request->input("status");

        $limit = $request->input("limit", 6);

        if ($id) {
            $food = Transactions::find($id);

            if ($food) {
                return ResponseFormatter::error("Food not found", 404);
            }
            return ResponseFormatter::success("OK", 200, $food);
        }


        $trx = Transactions::with(["user", 'food'])->where("user_id", Auth::user()->id);

        if ($food_id) {
            $trx->where("food_id", $food_id);
        }
        if ($status) {
            $trx->where("name",  $status);
        }



        return ResponseFormatter::success("Transaction list berhasil diambil", 200, $trx->paginate($limit));
    }
}
