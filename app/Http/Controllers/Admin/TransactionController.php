<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Transactions;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function all(Request $request, Store $store)
    {
        // $this->authorize('viewAny', [Transactions::class, $store]);

        $id = $request->input("food_id");
        $food_id = $request->input("food_id");
        $TrxStatus = $request->input("trx_status");
        $DelStatus = $request->input("delivery_status");

        $oderBy = $request->orderBy ?: "latest";
        $limit = $request->limit ?: 6;

        if ($id) {
            $food = Transactions::find($id);
            \abort_if(!$food, 404, "Transaction not found");

            return ResponseFormatter::success("OK", 200, $food);
        }

        $trx = Transactions::with(["user"])->withWhereHas("food", function ($query) use ($store) {
            $query->where('store_id', $store->id);
        });

        if ($food_id) {
            $trx->where("food_id", $food_id);
        }
        if ($TrxStatus) {
            $trx->where("trx_status", $TrxStatus);
        }
        if ($DelStatus) {
            $trx->where("delivery_status", $DelStatus);
        }
        if ($oderBy == "latest") {
            $trx->latest();
        }

        return ResponseFormatter::success("Transaction list berhasil diambil", 200, $trx->paginate($limit));
    }
}
