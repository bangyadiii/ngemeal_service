<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\PaymentLog;
use App\Models\Transactions;
use App\Services\Midtrans\Midtrans;
use App\Services\TransactionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Notification;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MidtransCallbackController extends Controller
{
    private $notif = null;
    public function __construct()
    {
        Midtrans::configureMidtrans();
    }

    public function callback(Request $request)
    {
        if (!app()->environment("local")) {
            $this->notif = new Notification();
            \info("hello world");
        } else {
            $this->notif = $request;
            \info("aa ");
        }

        if (!\app()->environment("local")) {
            if ($this->notif->signature_key !== $request->signature_key) {
                throw new BadRequestHttpException("Signature key is not valid");
            }
        }

        $transaction = $this->notif->transaction_status;
        $trxType = $this->notif->payment_type;
        $fraud = $this->notif->fraud_status;
        $orderId = $this->notif->order_id;

        try {
            $trx = Transactions::find($orderId);
            \abort_if(!$trx, 404, "Transaction not found");
            $trx->fill(["md_trx_id" => $this->notif->transaction_id]);

            if ($transaction == 'capture') {
                if ($trxType == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $trx->trx_status = "success";
                        $trx->delivery_status = "waiting_driver";
                    } else {
                        $trx->trx_status = "success";
                        $trx->delivery_status = "waiting_driver";
                    }
                }
            } elseif ($transaction == 'settlement') {
                $trx->trx_status = "success";
                $trx->delivery_status = "waiting_driver";
            } elseif (
                $transaction == 'cancel' ||
                $transaction == 'deny' ||
                $transaction == 'expire'
            ) {
                $trx->trx_status = "failed";
                $trx->delivery_status = "failed";
            } elseif ($transaction == 'pending') {
                $trx->trx_status = "pending";
            }

            DB::transaction(function () use ($trx) {
                $trx->save();
            }, 3);

            PaymentLog::create([
                "trx_id" => $trx->id,
                "md_trx_id" => $this->notif->transaction_id,
                "gross_amount" => $this->notif->gross_amount,
                "raw" => \json_encode(\collect($this->notif)->toArray()),
            ]);

            return ResponseFormatter::success("Transaction success", 200, [
                "transaction_detail" => $trx
            ]);
        } catch (Exception $e) {
            return ResponseFormatter::error("Transaction failed", 500, $e->getMessage());
        }
    }
}
