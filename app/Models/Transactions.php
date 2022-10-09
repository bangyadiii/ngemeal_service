<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transactions extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'food_id', 'user_id', 'quantity', 'total', 'status', 'payment_url', "metadata"
    ];

    public function food()
    {
        return $this->belongsTo(Food::class, 'id', 'food_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
