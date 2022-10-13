<?php

namespace App\Traits;

use App\Models\Food;
use App\Models\FoodImages;
use Illuminate\Support\Facades\Storage;

trait MediaUploadTrait
{
    //
    public function storeMedia($file, $modelName, $isPrimary = false, Food $food)
    {
        $name = \uniqid() . '-' . $file->getClientOriginalName();
        $filePath = Storage::putFileAs("tmp/$modelName", $file, $name);
        return [
            "food_id" => $food->id,
            "is_primary" => $isPrimary,
            "image_path" => $filePath
        ];
    }

    public function checkAndCreateDirIfNotExist($modelName)
    {
        if (!Storage::exists("tmp/$modelName")) {
            Storage::makeDirectory("tmp/$modelName");
        }
        if (!Storage::exists("previews/$modelName")) {
            Storage::makeDirectory("previews/$modelName");
        }
        if (!Storage::exists("thumb/$modelName")) {
            Storage::makeDirectory("thumb/$modelName");
        }
    }
}