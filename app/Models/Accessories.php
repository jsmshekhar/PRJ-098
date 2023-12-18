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
            $accessories = Accessories::where('user_slug', $auth->user_slug)->orderBy('created_at', 'DESC')->get();
            if (count($accessories) > 0) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['accessories' => $accessories]);
            } else {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['accessories' => []]);
            }
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
