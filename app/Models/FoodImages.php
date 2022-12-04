<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodImages extends Model
{
    use HasFactory;

    protected $fillable = [
        "food_id",
        "image_path",
        "image_url",
        "is_primary",
    ];
    public $timestamps = false;
    protected $hidden = [
        'deleted_at'
    ];
    public function food()
    {
        return $this->belongsTo("food");
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
