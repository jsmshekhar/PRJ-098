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
use App\Models\EvType;
class ProductCategory extends Model
{
    use HasFactory;

    protected $table = "product_categories";
    protected $primaryKey = 'product_category_id';

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : get getProductCategoryType
    --------------------------------------------------*/
    public function getProductCategoryType($request)
    {
        try {
            $auth = Auth::user();
            $product_categories = ProductCategory::where('user_id', $auth->user_id)->orderBy('created_at', 'DESC')->get();
            $ev_types = EvType::where('user_id', $auth->user_id)->orderBy('created_at', 'DESC')->get();
            if (count($product_categories) > 0 || count($ev_types) > 0) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['product_categories' => $product_categories, 'ev_types' => $ev_types]);
            } else {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['product_categories' => [], 'ev_types' => []]);
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
    Action    : addUpdateCategory
    --------------------------------------------------*/
    public function addUpdateCategory(Request $request)
    {
        try {
            $product_category_name = !empty($request->product_category_name) ? $request->product_category_name : "";
            $serial_number = !empty($request->serial_number) ? $request->serial_number : "";
            $item_in_stock = !empty($request->item_in_stock) ? $request->item_in_stock : "";
            $slug = !empty($request->slug) ? $request->slug : "";
            $auth = Auth::user();
            if (!empty($request->slug)) {
                $caretogory = ProductCategory::where('slug', $slug)->update([
                    "product_category_name" => $product_category_name,
                    "serial_number" => $serial_number,
                    "item_in_stock" => $item_in_stock,
                ]);
            } else {
                $slug = slug();
                $caretogory = ProductCategory::insertGetId([
                    "slug" => $slug,
                    "product_category_name" => $product_category_name,
                    "serial_number" => $serial_number,
                    "item_in_stock" => $item_in_stock,
                    "user_id" => $auth->user_id,
                    "user_slug" => $auth->slug,
                    "created_by" => $auth->user_id,
                ]);
            }
            if ($caretogory) {
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
