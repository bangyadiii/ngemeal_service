<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Uid\Ulid;

class Transactions extends Model
{
    use HasUlids;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'food_id',
        'user_id',
        'quantity',
        'total',
        'md_trx_id',
        'status',
        'payment_url',
        "md_snap_token",
        "metadata"
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected $hidden = [
        "md_trx_id",
        "deleted_at"
    ];

    // relationship
    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
