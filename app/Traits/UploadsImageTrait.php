<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait UploadsImageTrait
{
    public function uploadImage(UploadedFile $file, $folder)
    {
        $originalName = $file->getClientOriginalName();
        $fileName = time() . '-' . $originalName;
        $fileName = preg_replace('/\s+/', '', $fileName);
        $path = public_path($folder);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file->move($path, $fileName);
        return $fileName;
    }

    public function uploadMultipleImage($file, $folder, $filename)
    {
        $fileName = time() . '-' . $filename;
        $fileName = preg_replace('/\s+/', '', $fileName);
        $path = public_path($folder);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        file_put_contents($path . '/' . $fileName, $file);
        return $fileName;
    }
}
