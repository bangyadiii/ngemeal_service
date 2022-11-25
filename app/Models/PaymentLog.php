<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentLog extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        "trx_id",
        "md_trx_id",
        "gross_amount",
        "quantity",
        "raw"
    ];
}
