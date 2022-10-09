<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\PaymentLog;
use App\Models\Transactions;
use Exception;
use Illuminate\Http\Request;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function callback(Request $request)
    {
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $fraud = $notif->fraud_status;
        $trxID =  \explode("-", $notif->order_id)[0];
        try {
            $trx = Transactions::find($trxID);
            if ($transaction == 'capture') {
                if ($fraud == 'challenge') {
                    $trx->status = "pending";
                } elseif ($fraud == 'accept') {
                    $trx->status = "success";
                }
            } elseif ($transaction == 'settlement') {
                $trx->status = "success";
            } elseif (
                $transaction == 'cancel' ||
                $transaction == 'deny' ||
                $transaction == 'expire'
            ) {
                $trx->status = "failed";
            } elseif ($transaction == 'pending') {
                $trx->status = "pending";
            }
            PaymentLog::create([
                "order_id" => $notif->order_id,
                "trx_id" => $trx->id,
                "gross_amount" => $notif->gross_amount,
                "raw" => \json_encode($notif),
            ]);

            $trx->save();

            return ResponseFormatter::success("Transaction success", 200, [
                "transaction_detail" => $trx
            ]);
        } catch (Exception $e) {
            return ResponseFormatter::error("Transaction failed", 500, $e->getMessage());
        }
    }
}
