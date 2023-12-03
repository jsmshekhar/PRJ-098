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
class EvType extends Model
{
    use HasFactory;

    protected $table = "ev_types";
    protected $primaryKey = 'ev_type_id';

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : get getProductCategoryType
    --------------------------------------------------*/
    public function getEvType($request)
    {
        try {
            $auth = Auth::user();
            $ev_types = EvType::where('user_slug', $auth->user_slug)->orWhere('user_id', $auth->user_id)->orderBy('created_at', 'DESC')->get();
            $ev_categories = config('constants.EV_CATEGORIES');
            if (count($ev_types) > 0) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['ev_types' => $ev_types, 'ev_categories' => $ev_categories]);
            } else {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['ev_types' => [], 'ev_categories' => $ev_categories]);
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

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : addUpdateEvType
    --------------------------------------------------*/
    public function addUpdateEvType(Request $request)
    {
        try {
            $ev_type_name = !empty($request->ev_type_name) ? $request->ev_type_name : "";
            $range = !empty($request->range) ? $request->range : "";
            $speed = !empty($request->speed) ? $request->speed : "";
            $slug = !empty($request->slug) ? $request->slug : "";
            $ev_category = !empty($request->ev_category) ? $request->ev_category : "";

            $auth = Auth::user();
            if (!empty($request->slug)) {
                $ev_types = EvType::where('slug', $slug)->update([
                    "ev_type_name" => $ev_type_name,
                    "range" => $range,
                    "speed" => $speed,
                    "ev_category" => $ev_category,
                ]);
            } else {
                $slug = slug();
                $ev_types = EvType::insertGetId([
                    "slug" => $slug,
                    "ev_type_name" => $ev_type_name,
                    "range" => $range,
                    "speed" => $speed,
                    "ev_category" => $ev_category,
                    "user_id" => $auth->user_id,
                    "user_slug" => $auth->slug,
                    "created_by" => $auth->user_id,
                ]);
            }
            if ($ev_types) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'url' => route('add-update-product-category'),
                    'message' => !empty($slug) ? Lang::get('messages.UPDATE') : Lang::get('messages.INSERT'),
                ];
                return response()->json($status);
            } else {
                $status = [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'url' => "",
                    'message' => Lang::get('messages.INSERT_ERROR'),
                ];
                return response()->json($status);
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
