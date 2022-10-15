<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "user_id",
        "store_name",
        'address',
        "description",
        "rekening_number",
        "logo"
    ];

    // relational
    public function user()
    {
        $this->belongsTo(User::class);
    }
    public function food()
    {
        $this->hasMany(Food::class);
    }
}
