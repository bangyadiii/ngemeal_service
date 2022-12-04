<?php

namespace App\Services;

use App\Models\PaymentLog;
use App\Models\Transactions;

class TransactionService
{
    //
    public function __construct()
    {
        // // midtrans configuration
        // Config::$serverKey = config("services.midtrans.serverKey");
        // Config::$isProduction = config("services.midtrans.isProduction");
        // Config::$isSanitized = config("services.midtrans.isSanitized");
        // Config::$is3ds = config("services.midtrans.is3ds");

        // // create midtrans parameter

        // $customerDetails = [
        //     "name" => $user->name,
        //     "phone" => $user->phone,
        //     "email" => $user->email,
        // ];
        // $trxDetail = [
        //     "order_id" => $trx->id . "-" . rand(),
        //     "gross_amout" => $trx->total,
        // ];
        // $midtransParam  = [
        //     "transaction_details" => $trxDetail,
        //     "customer_details" => $customerDetails
        // ];

        // try {
        //     $snapUrl = \Midtrans\Snap::createTransaction($midtransParam)->redirect_url;

        //     $trx->forceFill(["redirect_url" => $snapUrl]);

        //     $trx->saveOrFail();

        //     return ResponseFormatter::success("Transaction Success.", 200, [
        //         "transaction" => $trx
        //     ]);
        // } catch (Exception $e) {
        //     return ResponseFormatter::error("Transaction failed.", 500, $e->getMessage());
        // }
    }

    public static function processPaymentStatus($this->notif, $trx)
    {
        $status = $this->notif->transaction_status;
        $fraud = $this->notif->fraud_status;


        if ($this->notif->status == 'capture') {
            if ($fraud == 'challenge') {
                $trx->status = "pending";
            } elseif ($fraud == 'accept') {
                $trx->status = "success";
            }
        } elseif ($status == 'settlement') {
            $trx->status = "success";
        } elseif (
            $status == 'cancel' ||
            $status == 'deny' ||
            $status == 'expire'
        ) {
            $trx->status = "failed";
        } elseif ($status == 'pending') {
            $trx->status = "pending";
        }

        $trx->save();

        PaymentLog::create([
            "trx_id" => $trx->id,
            "md_trx_id" => $this->notif->transaction_id,
            "gross_amount" => $this->notif->gross_amount,
            "raw" => \json_encode($this->notif),
        ]);

        return $trx->fresh();
    }
}
