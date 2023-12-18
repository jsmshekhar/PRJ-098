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
use App\Models\Hub;
use App\Models\ProductCategory;
use App\Models\EvType;
class Product extends Model
{
    use HasFactory;

    protected $table = "products";
    protected $primaryKey = 'product_id';

    /*--------------------------------------------------
    Developer : Raj Kumar
    Get Product
    --------------------------------------------------*/
    public function getProducts($request)
    {
        try {
            $auth = Auth::user();
            $products = Product::where('user_slug', $auth->user_slug)->orderBy('created_at', 'DESC')->get();
            if (count($products)>0) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['products' => $products]);
            } else {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['products' => []]);
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
    Create Product
    --------------------------------------------------*/
    public function createProduct($evtype)
    {
        try {
            $auth = Auth::user();
            $product_categories = ProductCategory::orderBy('created_at', 'DESC')->get();
            $ev_types = EvType::orderBy('created_at', 'DESC')->get();
            $hubs = Hub::select('hub_id', 'city')->orderBy('created_at', 'DESC')->get();
            $ev_categories = config('constants.EV_CATEGORIES');
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['product_categories' => $product_categories, 'ev_types' => $ev_types, 'ev_categories' => $ev_categories, 'hubs' => $hubs]);
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
    Action    : add product
    --------------------------------------------------*/
    public function addProduct(Request $request)
    {
        try {
            $title = !empty($request->title) ? $request->title : "";
            $speed = !empty($request->speed) ? $request->speed : "";
            $ev_number = !empty($request->ev_number) ? $request->ev_number : "";
            $rent_per_day = !empty($request->rent_per_day) ? $request->rent_per_day : "";
            $description = !empty($request->description) ? $request->description : "";
            $product_category_id = !empty($request->product_category_id) ? $request->product_category_id : "";
            $ev_category = !empty($request->ev_category) ? $request->ev_category : "";
            $ev_type_id = !empty($request->ev_type_id) ? $request->ev_type_id : "";
            $profile_category = !empty($request->profile_category) ? $request->profile_category : "";
            $hub_id = !empty($request->hub_id) ? $request->hub_id : "";
            $auth = Auth::user();
            
            $slug = slug();
            $product = Product::insertGetId([
                "slug" => $slug,
                "title" => $title,
                "ev_number" => $ev_number,
                "rent_per_day" => $rent_per_day,
                "speed" => $speed,
                "description" => $description,
                "product_category_id" => $product_category_id,
                "ev_category" => $ev_category,
                "ev_type_id" => $ev_type_id,
                "profile_category" => $profile_category,
                "hub_id" => $hub_id,
                "user_id" => $auth->user_id,
                "user_slug" => $auth->slug,
                "created_by" => $auth->user_id,
            ]);
            $profileCategory = $profile_category == 1 ? 'corporate' : ($profile_category == 2 ? 'individual' : ($profile_category == 3 ? 'student' : 'vendor'));
            if ($product) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'url' => route('products', $profileCategory),
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
    Action    : add product
    --------------------------------------------------*/
    public function updateProduct(Request $request)
    {
        try {
            $title = !empty($request->title) ? $request->title : "";
            $speed = !empty($request->speed) ? $request->speed : "";
            $ev_number = !empty($request->ev_number) ? $request->ev_number : "";
            $rent_per_day = !empty($request->rent_per_day) ? $request->rent_per_day : "";
            $description = !empty($request->description) ? $request->description : "";
            $product_category = !empty($request->product_category) ? $request->product_category : "";
            $ev_category = !empty($request->ev_category) ? $request->ev_category : "";
            $ev_type = !empty($request->ev_type) ? $request->ev_type : "";
            $profile_category = !empty($request->profile_category) ? $request->profile_category : "";
            $hub_id = !empty($request->hub_id) ? $request->hub_id : "";
            $slug = !empty($request->slug) ? $request->slug : "";
            $auth = Auth::user();

            $product = Product::where('slug', $slug)->update([
                "title" => $title,
                "ev_number" => $ev_number,
                "rent_per_day" => $rent_per_day,
                "speed" => $speed,
                "description" => $description,
                "product_category" => $product_category,
                "ev_category" => $ev_category,
                "ev_type" => $ev_type,
                "profile_category" => $profile_category,
                "hub_id" => $hub_id,
                "user_id" => $auth->user_id,
                "user_slug" => $auth->slug,
                "created_by" => $auth->user_id,
            ]);
            $profileCategory = $profile_category == 1 ? 'corporate' : ($profile_category == 2 ? 'individual' : ($profile_category == 3 ? 'student' : 'vendor'));
            if ($product) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'url' => route('products', $profileCategory),
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
