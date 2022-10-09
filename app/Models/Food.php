<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class Food extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =  [
        'name', 'description', 'ingredients', 'price', 'rate', 'types', "picturePath"
    ];

    public function transactions()
    {
        $this->hasMany(Transactions::class);
    }

    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray["picturePath"] = $this->picturePath;
        return $toArray;
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
}
