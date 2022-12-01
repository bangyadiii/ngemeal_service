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

        if ($image1 = $request->images_1) {
            $this->uploadAndCheck($image1, $request->priority_1, $imagesArr);
        }

        if ($image2 = $request->images_2) {

            $this->uploadAndCheck($image2, $request->priority_2, $imagesArr);
        }

        if ($image3 = $request->images_4) {

            $this->uploadAndCheck($image3, $request->priority_3, $imagesArr);
        }

        $food->images()->createMany($imagesArr);

        return ResponseFormatter::success("CREATED", 201, $food->load("images"));
    }

    function uploadAndCheck(UploadedFile $file, $priority, &$array)
    {
        $path = $this->storeMedia($file, self::$modelName);

        if (!$path) {
            return ResponseFormatter::error("Occur while uploading photo", 500, $path);
        }
        $imagePath['image_path'] = $path;
        $imagePath['image_url'] = Storage::url($path);
        $imagePath['priority'] = $priority;
        $array[] = $imagePath;

        return $array;
    }
}
