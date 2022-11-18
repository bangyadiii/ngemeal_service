<?php

namespace App\Traits;

use App\Models\Food;
use App\Models\FoodImages;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Psy\Readline\Hoa\FileException;

trait MediaUploadTrait
{
    //
    public function storeMedia(UploadedFile $file, $modelName): string
    {
        $name = \uniqid() . '-' . $file->getClientOriginalName();
        $filePath = Storage::putFileAs("tmp/$modelName", $file, $name);
        \throw_if(!$filePath, FileException::class, "Cannot upload media.");

        return $filePath;
    }


    public function removeMedia(string $path): bool
    {
        $isDeleted = Storage::disk('public')->delete($path);
        \throw_if(!$isDeleted, FileException::class, "Can't delete the file.");

        return true;
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
