<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodImages extends Model
{
    use HasFactory;

    protected $fillable = [
        "food_id",
        "image_path",
        "is_primary"
    ];
    public $timestamps = false;

    public function food()
    {
        return $this->belongsTo("food");
    }
}
