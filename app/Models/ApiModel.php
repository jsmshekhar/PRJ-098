<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class ApiModel extends Model
{
    use HasFactory;

    public function clean($string)
    {
        $string = str_replace(' ', '-', $string);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }

    public static function uploadFile($request)
    {
        try {
            if ($request->hasFile('file_name')) {
                $filePath = $request->path ?? "";
                $file = $request->file('file_name');

                $uploadedPath = 'upload/' . $filePath;

                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $fileName = strtolower($fileName) . time() . '.' . $file->extension();

                $uploadStatus = $request->file_name->move(public_path($uploadedPath), $fileName);
                if ($uploadStatus) {
                    $returnPath = asset('public/' . $uploadedPath . '/' . $fileName);
                    return successResponse(Response::HTTP_OK, Lang::get('messages.UPLOAD_SUCCESS'), [
                        'fileName' => $fileName,
                        'filePath' => $returnPath,
                    ]);
                }
            }
            return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('messages.UPLOAD_ERROR'));
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }
}
