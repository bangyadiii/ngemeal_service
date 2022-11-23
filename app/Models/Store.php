<?php

namespace App\Models;

use Carbon\Carbon;
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
        "logo_path"
    ];

    // relational
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function food()
    {
        return $this->hasMany(Food::class);
    }

    // attributes
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
}
