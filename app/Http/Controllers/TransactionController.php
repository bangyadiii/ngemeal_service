<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\CheckoutProductRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Food;
use App\Models\Transactions;
use App\Services\Midtrans\CreateSnapTokenService;
use Illuminate\Http\Request;


class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input("food_id");
        $food_id = $request->input("food_id");
        $TrxStatus = $request->input("trx_status");
        $DelStatus = $request->input("delivery_status");

        $limit = $request->input("limit", 6);

        if ($id) {
            $food = Transactions::find($id);

            if ($food) {
                return ResponseFormatter::error("Food not found", 404);
            }
            return ResponseFormatter::success("OK", 200, $food);
        }


        $trx = Transactions::with(["user", 'food'])->where("user_id", $request->user()->id);

        if ($food_id) {
            $trx->where("food_id", $food_id);
        }
        if ($TrxStatus) {
            $trx->where("trx_status", $TrxStatus);
        }
        if ($DelStatus) {
            $trx->where("delivery_status", $DelStatus);
        }

        return ResponseFormatter::success("Transaction list berhasil diambil", 200, $trx->paginate($limit));
    }

    public function update(UpdateTransactionRequest $request, $id)
    {
        $trx = Transactions::find($id);
        \abort_if(!$trx, 404, "Transaction not found");

        $trx->fill($request->validated())->save();

        return ResponseFormatter::success("Trx has been updated", 200, $trx);
    }

    public function checkout(CheckoutProductRequest $request)
    {
        $user = $request->user();
        $product = Food::find($request->food_id);

        \abort_if(!$product, 404, "Product not found.");

        $grossAmount = $product->price * $request->quantity;
        $metadata = \json_encode([
            "user" => $user,
            "product" => $product,
        ]);

        $trx = Transactions::create([
            "food_id" => $product->id,
            "user_id" => $user->id,
            "quantity" => $request->quantity,
            "total" => $grossAmount,
            "metadata" => $metadata,
        ]);


        try {
            $data = CreateSnapTokenService::createSnapUrl($trx);

            $trx->forceFill([
                "payment_url" => $data->redirect_url,
                "md_snap_token" => $data->token,
            ])->saveOrfail();

            return ResponseFormatter::success("Transaction success.", 200, $trx->fresh());
        } catch (\Throwable $th) {
            $trx->forceDelete();
            return ResponseFormatter::error("Failed to create transaction.", 500, $th->getMessage());
        }
    }
}
