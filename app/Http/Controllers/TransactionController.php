<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\CheckoutProductRequest;
use App\Http\Requests\UpdateTransactionRequest;
use \Midtrans\Config;
use App\Models\Transactions;
use Exception;
use Illuminate\Http\Request;

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


        $trx = Transactions::with(["user", 'food'])->where("user_id", $request->user()->id);

        if ($food_id) {
            $trx->where("food_id", $food_id);
        }
        if ($status) {
            $trx->where("name", $status);
        }

        return ResponseFormatter::success("Transaction list berhasil diambil", 200, $trx->paginate($limit));
    }

    public function update(UpdateTransactionRequest $request, $id)
    {
        $trx = Transactions::findOrFail($id);
        $trx->fill($request->validated())->save();

        return ResponseFormatter::success("Trx has been updated", 200, $trx);
    }

    public function checkout(CheckoutProductRequest $request)
    {
        $user = $request->user();
        $trx = Transactions::create([
            "food_id" => $request->food_id,
            "user_id" => $request->user_id,
            "quantity" => $request->quantity,
            "total" => $request->total,
            "status" => $request->status,
        ]);

        // midtrans configuration
        Config::$serverKey = config("services.midtrans.serverKey");
        Config::$isProduction = config("services.midtrans.isProduction");
        Config::$isSanitized = config("services.midtrans.isSanitized");
        Config::$is3ds = config("services.midtrans.is3ds");

        // create midtrans parameter

        $customerDetails = [
            "name" => $user->name,
            "phone" => $user->phone,
            "email" => $user->email,
        ];
        $trxDetail = [
            "order_id" => $trx->id . "-" . rand(),
            "gross_amout" => $trx->total,
        ];
        $midtransParam  = [
            "transaction_details" => $trxDetail,
            "customer_details" => $customerDetails
        ];

        try {
            $snapUrl = \Midtrans\Snap::createTransaction($midtransParam)->redirect_url;

            $trx->fill(["redirect_url" => $snapUrl]);

            $trx->save();

            return ResponseFormatter::success("Transaction Success.", 200, [
                "transaction" => $trx
            ]);
        } catch (Exception $e) {
            return ResponseFormatter::error("Transaction failed.", 500, $e->getMessage());
        }
    }
}
