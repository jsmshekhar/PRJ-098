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
                    DB::raw('CASE WHEN bike_type = 1 THEN "Cargo Bike" WHEN bike_type = 2 THEN "Normal Bike" END AS bike_type'),
                    DB::raw('CASE WHEN profile_category = 1 THEN "corporate" WHEN profile_category = 2 THEN "individual" WHEN profile_category = 3 THEN "student" WHEN profile_category = 4 THEN "vendor" END AS profile_category'),
                    DB::raw('CASE WHEN status_id = 1 THEN "In Stock" WHEN status_id = 2 THEN "Inacticve" WHEN status_id = 3 THEN "NF" WHEN status_id = 4 THEN "Assigned" WHEN status_id = 5 THEN "Delete" END AS status_id')
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
            $vehicleStatus = config('constants.VEHICLE_STATUS');
            $bike_types = config('constants.BIKE_TYPE');
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['rent_cycles' => $rent_cycles, 'ev_types' => $ev_types, 'ev_categories' => $ev_categories, 'hubs' => $hubs, 'battery_types' => $battery_types, 'vehicleStatus' => $vehicleStatus, 'bike_types' => $bike_types]);
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
            $ev_category = !empty($request->ev_category) ? $request->ev_category : null;
            $ev_type_id = !empty($request->ev_type_id) ? $request->ev_type_id : null;
            $profile_category = !empty($request->profile_category) ? $request->profile_category : null;
            $battery_type = !empty($request->battery_type) ? $request->battery_type : null;
            $rent_cycle = !empty($request->rent_cycle) ? $request->rent_cycle : null;
            $hub_id = !empty($request->hub_id) ? $request->hub_id : null;
            $chassis_number = !empty($request->chassis_number) ? $request->chassis_number : "";
            $gps_emei_number = !empty($request->gps_emei_number) ? $request->gps_emei_number : "";
            $km_per_charge = !empty($request->km_per_charge) ? $request->km_per_charge : "";
            $is_display_on_app = !empty($request->is_display_on_app) ? 1 : 2;
            $status_id = !empty($request->status_id) ? $request->status_id : 1;
            $bike_type = !empty($request->bike_type) ? $request->bike_type : 2;
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
                "bike_type" => $bike_type,
                'image' => !empty($product_image) ? $product_image : "",
                "user_id" => $auth->user_id,
                "user_slug" => $auth->slug,
                "created_by" => $auth->user_id,
                "status_id" => $status_id,
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
            $vehicleStatus = config('constants.VEHICLE_STATUS');
            $bike_types = config('constants.BIKE_TYPE');
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['product'=> $product, 'rent_cycles' => $rent_cycles, 'ev_types' => $ev_types, 'ev_categories' => $ev_categories, 'hubs' => $hubs, 'battery_types' => $battery_types, 'vehicleStatus' =>$vehicleStatus, 'bike_types' => $bike_types]);
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
            $ev_category = !empty($request->ev_category) ? $request->ev_category : null;
            $ev_type_id = !empty($request->ev_type_id) ? $request->ev_type_id : null;
            $profile_category = !empty($request->profile_category) ? $request->profile_category : null;
            $battery_type = !empty($request->battery_type) ? $request->battery_type : null;
            $rent_cycle = !empty($request->rent_cycle) ? $request->rent_cycle : null;
            $hub_id = !empty($request->hub_id) ? $request->hub_id : null;
            $chassis_number = !empty($request->chassis_number) ? $request->chassis_number : "";
            $gps_emei_number = !empty($request->gps_emei_number) ? $request->gps_emei_number : "";
            $km_per_charge = !empty($request->km_per_charge) ? $request->km_per_charge : "";
            $is_display_on_app = !empty($request->is_display_on_app) ? 1 : 2;
            $status_id = !empty($request->status_id) ? $request->status_id : 1;
            $bike_type = !empty($request->bike_type) ? $request->bike_type : 2;
           
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
                    "bike_type" => $bike_type,
                    'image' => $product_image,
                    "user_id" => $auth->user_id,
                    "user_slug" => $auth->slug,
                    "updated_by" => $auth->user_id,
                    "status_id" => $status_id,
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
                    "bike_type" => $bike_type,
                    "user_id" => $auth->user_id,
                    "user_slug" => $auth->slug,
                    "updated_by" => $auth->user_id,
                    "status_id" => $status_id,
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

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Delete Product(Vehicle)
    --------------------------------------------------*/
    public function deleteProduct($slug)
    {
        try {
            $deleteResult = Product::where('slug', $slug);
            $result = $deleteResult->delete();
            Product::where('slug', $slug)->update([
                "status_id" => 5, // delete
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

    /*--------------------------------------------------
    Developer : Raj Kumar
    All vehicles list with mobilized demobilized
    --------------------------------------------------*/
    public function getAssignedVehicles($request)
    {
        try {
            $auth = Auth::user();
            $vehicles = RiderOrder::join('products', function($q) {
                    $q->on('products.product_id', '=', 'rider_orders.mapped_vehicle_id');
                    $q->where('products.status_id', '=', 4);
                })
                ->join('riders', 'riders.rider_id', '=', 'rider_orders.rider_id')
                ->join('hubs', 'hubs.hub_id', '=', 'products.hub_id')
                ->where('rider_orders.status_id','=', 1)
                ->select(
                    'rider_orders.payment_status',
                    'products.ev_number',
                    DB::raw('CASE 
                        WHEN products.ev_category_id = 1 THEN "Two Wheeler" 
                        WHEN products.ev_category_id = 2 THEN "Three Wheeler" 
                        ELSE ""
                    END as ev_category_name'),
                    'riders.name','riders.phone','riders.customer_id','riders.slug',
                    DB::raw('CASE 
                        WHEN riders.profile_type = 1 THEN "Corporate" 
                        WHEN riders.profile_type = 2 THEN "Individual" 
                        WHEN riders.profile_type = 3 THEN "Student" 
                        WHEN riders.profile_type = 4 THEN "Vendor" 
                        ELSE "" 
                    END as profile_category_name'),
                    'hubs.hubid'
                );
            if (isset($request->is_search) && $request->is_search == 1) {
                if (isset($request->ev_no) && !empty($request->ev_no)) {
                    $vehicles = $vehicles->where('products.ev_number', 'LIKE', "%{$request->ev_no}%");
                }
                if (isset($request->ev_cat) && !empty($request->ev_cat)) {
                    $vehicles = $vehicles->where('products.ev_category_id', 'LIKE', "%{$request->ev_cat}%");
                }
                if (isset($request->cus_id) && !empty($request->cus_id)) {
                    $vehicles = $vehicles->where('riders.customer_id', 'LIKE', "%{$request->cus_id}%");
                }
                if (isset($request->ph) && !empty($request->ph)) {
                    $vehicles = $vehicles->where('riders.phone', 'LIKE', "%{$request->ph}%");
                }
                if (isset($request->hid) && !empty($request->hid)) {
                    $vehicles = $vehicles->where('hubs.hub_id', 'LIKE', "%{$request->hid}%");
                }
                if (isset($request->pay) && !empty($request->pay)) {
                    $vehicles = $vehicles->where('rider_orders.payment_status', 'LIKE', "%{$request->pay}%");
                }
                // if (isset($request->status) && !empty($request->status)) {
                //     $vehicles = $vehicles->where('rider_orders.payment_status', 'LIKE', "%{$request->status}%");
                // }
            }
            $vehicles = $vehicles->orderBy('rider_orders.created_at', 'DESC')->paginate(20);
            $count = RiderOrder::join('products', function ($q) {
                    $q->on('products.product_id', '=', 'rider_orders.mapped_vehicle_id');
                    $q->where('products.status_id', '=', 4);
                })
                ->join('riders', 'riders.rider_id', '=', 'rider_orders.rider_id')
                ->join('hubs', 'hubs.hub_id', '=', 'products.hub_id')
                ->where('rider_orders.status_id', '=', 1)->count();
            $hubs = DB::table('hubs')->whereNull('deleted_at')->where('status_id', 1)->select('hub_id', 'city','hubid')->get();
            $payment_status = config('constants.PAYMENT_STATUS');
            $vehicle_status = config('constants.VEHICLE_STATUS');
            $ev_category = config('constants.EV_CATEGORIES');
            if (count($vehicles) > 0) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['vehicles' => $vehicles, 'count' => $count, 'hubs' => $hubs, 'payment_status' => $payment_status, 'vehicle_status' => $vehicle_status, 'ev_category' => $ev_category]);
            } else {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['vehicles' => [], 'count' => "",'hubs' => $hubs, 'payment_status' => $payment_status, 'vehicle_status' => $vehicle_status, 'ev_category' => $ev_category]);
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
