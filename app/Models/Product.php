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
use App\Traits\UploadsImageTrait;
use App\Models\EvType;
use Nette\Utils\Image;
class Product extends Model
{
    use HasFactory, UploadsImageTrait;

    protected $table = "products";
    protected $primaryKey = 'product_id';

    /*--------------------------------------------------
    Developer : Raj Kumar
    Get Product
    --------------------------------------------------*/
    public function getProducts($request, $param)
    {
        try {
            $auth = Auth::user();
            $products = Product::select(
                    'title','slug','image',
                    DB::raw('CASE WHEN profile_category = 1 THEN "corporate" WHEN profile_category = 2 THEN "individual" WHEN profile_category = 3 THEN "student" WHEN profile_category = 4 THEN "vendor" END AS profile_category')
            )->where('hub_id', $auth->hub_id)->orWhere('hub_id', '!=', $auth->hub_id);

            if ($param == 'corporate') {
                $products = $products->where('profile_category', 1);
            }
            if ($param == 'individual') {
                $products = $products->where('profile_category', 2);
            }
            if ($param == 'student') {
                $products = $products->where('profile_category', 3);
            }
            if ($param == 'vendor') {
                $products = $products->where('profile_category', 4);
            }
             $products = $products->orderBy('created_at', 'DESC')->paginate(20);
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
    public function createProduct($param)
    {
        try {
            $auth = Auth::user();
            $ev_types = EvType::orderBy('created_at', 'DESC')->get();
            $hubs = Hub::select('hub_id', 'city')->orderBy('created_at', 'DESC')->get();
            $ev_categories = config('constants.EV_CATEGORIES');
            $rent_cycles = config('constants.RENT_CYCLE');
            $battery_types = config('constants.BATTERY_TYPE');
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['rent_cycles' => $rent_cycles, 'ev_types' => $ev_types, 'ev_categories' => $ev_categories, 'hubs' => $hubs, 'battery_types' => $battery_types]);
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
            $per_day_rent = !empty($request->per_day_rent) ? $request->per_day_rent : "";
            $description = !empty($request->description) ? $request->description : "";
            $ev_category = !empty($request->ev_category) ? $request->ev_category : "";
            $ev_type_id = !empty($request->ev_type_id) ? $request->ev_type_id : "";
            $profile_category = !empty($request->profile_category) ? $request->profile_category : "";
            $battery_type = !empty($request->battery_type) ? $request->battery_type : "";
            $rent_cycle = !empty($request->rent_cycle) ? $request->rent_cycle : "";
            $hub_id = !empty($request->hub_id) ? $request->hub_id : "";
            $chassis_number = !empty($request->chassis_number) ? $request->chassis_number : "";
            $gps_emei_number = !empty($request->gps_emei_number) ? $request->gps_emei_number : "";
            $km_per_charge = !empty($request->km_per_charge) ? $request->km_per_charge : "";
            $is_display_on_app = !empty($request->is_display_on_app) ? 1 : 2;
            $product_image = '';
            if ($request->image) {
                $image = $request->file('image');
                $folder = '/upload/product/';
                $product_image = $this->uploadImage($image, $folder);
            }
            
            $auth = Auth::user();
            
            $slug = slug();
            $product = Product::insertGetId([
                "slug" => $slug,
                "title" => $title,
                "ev_number" => $ev_number,
                "chassis_number" => $chassis_number,
                "gps_emei_number" => $gps_emei_number,
                "km_per_charge" => $km_per_charge,
                "per_day_rent" => $per_day_rent,
                "speed" => $speed,
                "description" => $description,
                "bettery_type" => $battery_type,
                "rent_cycle" => $rent_cycle,
                "ev_category_id" => $ev_category,
                "ev_type_id" => $ev_type_id,
                "profile_category" => $profile_category,
                "hub_id" => $hub_id,
                "is_display_on_app" => $is_display_on_app,
                'image' => !empty($product_image) ? $product_image : "",
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
    Edit Product
    --------------------------------------------------*/
    public function editProduct($slug, $param)
    {
        try {
            $auth = Auth::user();
            $product = Product::where('slug', $slug)->first();
            $ev_types = EvType::orderBy('created_at', 'DESC')->get();
            $hubs = Hub::select('hub_id', 'city')->orderBy('created_at', 'DESC')->get();
            $ev_categories = config('constants.EV_CATEGORIES');
            $rent_cycles = config('constants.RENT_CYCLE');
            $battery_types = config('constants.BATTERY_TYPE');
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['product'=> $product, 'rent_cycles' => $rent_cycles, 'ev_types' => $ev_types, 'ev_categories' => $ev_categories, 'hubs' => $hubs, 'battery_types' => $battery_types]);
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
    public function updateProduct(Request $request, $slug)
    {
        try {
            $title = !empty($request->title) ? $request->title : "";
            $speed = !empty($request->speed) ? $request->speed : "";
            $ev_number = !empty($request->ev_number) ? $request->ev_number : "";
            $per_day_rent = !empty($request->per_day_rent) ? $request->per_day_rent : "";
            $description = !empty($request->description) ? $request->description : "";
            $ev_category = !empty($request->ev_category) ? $request->ev_category : "";
            $ev_type_id = !empty($request->ev_type_id) ? $request->ev_type_id : "";
            $profile_category = !empty($request->profile_category) ? $request->profile_category : "";
            $battery_type = !empty($request->battery_type) ? $request->battery_type : "";
            $rent_cycle = !empty($request->rent_cycle) ? $request->rent_cycle : "";
            $hub_id = !empty($request->hub_id) ? $request->hub_id : "";
            $chassis_number = !empty($request->chassis_number) ? $request->chassis_number : "";
            $gps_emei_number = !empty($request->gps_emei_number) ? $request->gps_emei_number : "";
            $km_per_charge = !empty($request->km_per_charge) ? $request->km_per_charge : "";
            $is_display_on_app = !empty($request->is_display_on_app) ? 1 : 2;
            $product_image = '';
            if ($request->image) {
                $image = $request->file('image');
                $folder = '/upload/product/';
                $product_image = $this->uploadImage($image, $folder);
            }
            $auth = Auth::user();
            if( $product_image){
                $product = Product::where('slug', $slug)->update([
                    "title" => $title,
                    "ev_number" => $ev_number,
                    "chassis_number" => $chassis_number,
                    "gps_emei_number" => $gps_emei_number,
                    "km_per_charge" => $km_per_charge,
                    "per_day_rent" => $per_day_rent,
                    "speed" => $speed,
                    "description" => $description,
                    "bettery_type" => $battery_type,
                    "rent_cycle" => $rent_cycle,
                    "ev_category_id" => $ev_category,
                    "ev_type_id" => $ev_type_id,
                    "profile_category" => $profile_category,
                    "hub_id" => $hub_id,
                    "is_display_on_app" => $is_display_on_app,
                    'image' => $product_image,
                    "user_id" => $auth->user_id,
                    "user_slug" => $auth->slug,
                    "updated_by" => $auth->user_id,
                ]);
            }else{
                $product = Product::where('slug', $slug)->update([
                    "title" => $title,
                    "ev_number" => $ev_number,
                    "chassis_number" => $chassis_number,
                    "gps_emei_number" => $gps_emei_number,
                    "km_per_charge" => $km_per_charge,
                    "per_day_rent" => $per_day_rent,
                    "speed" => $speed,
                    "description" => $description,
                    "bettery_type" => $battery_type,
                    "rent_cycle" => $rent_cycle,
                    "ev_category_id" => $ev_category,
                    "ev_type_id" => $ev_type_id,
                    "profile_category" => $profile_category,
                    "hub_id" => $hub_id,
                    "is_display_on_app" => $is_display_on_app,
                    "user_id" => $auth->user_id,
                    "user_slug" => $auth->slug,
                    "updated_by" => $auth->user_id,
                ]);
            }
           
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
