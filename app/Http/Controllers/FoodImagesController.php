<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Food;
use App\Traits\MediaUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FoodImagesController extends Controller
{
    use MediaUploadTrait;

    private static $modelName = "food";
    public function store(Request $request, Food $food)
    {
        $imagesArr = array();
        $this->checkAndCreateDirIfNotExist(self::$modelName);

        if ($image1 = $request->images_primary) {
            $this->uploadAndCheck($image1, true, $imagesArr);
        }

        if ($request->hasFile("images")) {
            foreach ($request->file("images", []) as $img) {
                $this->uploadAndCheck($img, false, $imagesArr);
            }
        }
        $food->images()->createMany($imagesArr);

        return ResponseFormatter::success("CREATED", 201, $food->load("images"));
    }

    function uploadAndCheck(UploadedFile $file, $isPrimary, &$array)
    {
        $path = $this->storeMedia($file, self::$modelName);

        if (!$path) {
            return ResponseFormatter::error("Occur while uploading photo", 500, $path);
        }
        $imagePath['image_path'] = $path;
        $imagePath['image_url'] = Storage::url($path);
        $imagePath['is_primary'] = $isPrimary;
        $array[] = $imagePath;

        return $array;
    }
}
