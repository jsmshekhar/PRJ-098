<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class Accessories extends Model
{
    use HasFactory;
    protected $table = "accessories";
    protected $primaryKey = 'accessories_id';

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : get Accessories
    --------------------------------------------------*/
    public function getAccessories($request)
    {
        try {
            $auth = Auth::user();
            $accessories = Accessories::select(
                'title', 'image','slug',
                'no_of_item',
                'price',
                'accessories_category_id',
                DB::raw('CASE WHEN accessories_category_id = 1 THEN "Helmet" WHEN accessories_category_id = 2 THEN "T-Shirt" WHEN accessories_category_id = 3 THEN "Mobile Holder" END AS accessories_category')
            )
            ->where('created_by', $auth->user_id)->orderBy('created_at', 'DESC')->get();
            
            $accessories_categories = config('constants.ACCESSORIES_CATEGORY');
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['accessories' => $accessories, 'accessories_categories' => $accessories_categories]);
           
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
