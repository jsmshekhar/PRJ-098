<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class ComplainCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "complain_categories";
    protected $primaryKey = 'complain_category_id';

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Delete Complain Category
    --------------------------------------------------*/
    public function deleteComplainCategorySlug($slug)
    {
        try {
            $deleteResult = ComplainCategory::where('slug', $slug);
            $result = $deleteResult->delete();
            ComplainCategory::where('slug', $slug)->update([
                "status_id" => 4,
            ]);

            if (!empty($result)) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.DELETE'), $result);
            } else {
                return errorResponse(Response::HTTP_OK, Lang::get('messages.DELETE_ERROR'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }
}
