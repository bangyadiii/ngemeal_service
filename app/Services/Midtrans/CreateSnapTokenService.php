<?php

namespace App\Services\Midtrans;

use App\Models\Food;
use App\Models\Transactions;
use App\Models\User;
use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{
    protected Transactions $trx;
    protected User $user;
    protected Food $product;

    private function __construct(Transactions $trx)
    {
        parent::__construct();

        $this->trx = $trx;
        $this->user = $trx->user;
        $this->product = $trx->food;
    }

    public function getRedirectUrl()
    {
        $params = [
            'transaction_details' => [
                'order_id' => $this->trx->id,
                'gross_amount' => $this->trx->total,
            ],
            "item_details" => [
                [
                    "id" => $this->product->id,
                    "name" => $this->product->name,
                    "price" => $this->product->price,
                    "quantity" => $this->trx->quantity,
                ]
            ],
            'customer_details' => [
                "name" => $this->user->name,
                "email" => $this->user->email,
            ]
        ];
        $data = Snap::createTransaction($params);
        return $data;
    }

    public static function createSnapUrl(Transactions $trx)
    {
        $instance =  new CreateSnapTokenService($trx);
        $data = $instance->getRedirectUrl();
        return $data;
    }
}
