<?php

namespace App\Models;

use Carbon\Carbon;
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
    protected $hidden = [
        'deleted_at'
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
}
