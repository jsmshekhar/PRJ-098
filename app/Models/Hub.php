<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Models\Product;
class Hub extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "hubs";
    protected $primaryKey = 'hub_id';

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : get hubs
    --------------------------------------------------*/
    public function getHubs($request)
    {
        try {
            $auth = Auth::user();
            $perPage = env('PER_PAGE');
            if (isset($request->per_page) && $request->per_page > 0) {
                $perPage = $request->per_page;
            }
            $hubs = Hub::where('hub_id', $auth->hub_id)->orWhere('created_by',$auth->user_id)->whereNull('deleted_at');
            if (isset($request->is_search) && $request->is_search == 1) {
                if (isset($request->hub_id) && !empty($request->hub_id)) {
                    $hubs = $hubs->where('hubId', 'LIKE', "%{$request->hub_id}%");
                }
                if (isset($request->city) && !empty($request->city)) {
                    $hubs = $hubs->where('city', 'LIKE', "%{$request->city}%");
                }
                if (isset($request->hub_capacity) && !empty($request->hub_capacity)) {
                    $hubs = $hubs->where('hub_limit', 'LIKE', "%{$request->hub_capacity}%");
                }
                if (isset($request->vehicle) && !empty($request->vehicle)) {
                    // $hubs = $hubs->where('hub_limit', 'LIKE', "%{$request->vehicle}%");
                }
            }

            $hubs = $hubs->orderBy('created_at', 'DESC')->paginate($perPage);
            if(count($hubs)>0){
                foreach ($hubs as $key => $value) {
                    $value->vehicle_count = DB::table('products')->where('hub_id', $value->hub_id)->count();
                }
            }
            $lastHub = Hub::orderBy('hub_id', 'DESC')->first();
            $hubId = "101";
            if ($lastHub) {
                $length = 2;
                $lastId = substr($lastHub->hubId, $length);
                $hubLast = $lastId ? (int)$lastId + 1 : 101;
                $hubId = (string)$hubLast;
            }
            if (count($hubs) > 0) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['hubs' => $hubs, 'hubId' => $hubId]);
            } else {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['hubs' => [], 'hubId' => $hubId]);
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
    Action    : view hub
    --------------------------------------------------*/
    public function viewHub($request, $slug, $param)
    {
        try {
            $perPage = env('PER_PAGE');
            if (isset($request->per_page) && $request->per_page > 0) {
                $perPage = $request->per_page;
            }
            $vehicles = [];
            $employees = [];
            $roles = [];
            $rent_cycles = [];
            $ev_types = [];
            $ev_categories = [];
            $battery_types = [];
            $hub_parts = [];
            $bike_types = [];
            $profile_categories = [];
            $vehicleStatus = [];
            $accessories_categories = [];
            $count = 0;
            $hub = Hub::where('slug', $slug)->whereNull('deleted_at')->first();
            $empCount = User::where('hub_id', $hub->hub_id)->whereNull('deleted_at')->count();
            $vehicleCount = Product::where('hub_id', $hub->hub_id)->whereNull('deleted_at')->count();
            $accessoriesinHub = array_unique(HubPartAccessories::where('hub_id', $hub->hub_id)->pluck('accessories_category_id')->toArray());
            if($param == 'vehicle'){
                $vehicles = Product::leftJoin('rider_orders', 'rider_orders.mapped_vehicle_id', '=', 'products.product_id')
                ->leftJoin('riders', 'riders.rider_id', '=', 'rider_orders.rider_id')
                ->leftJoin('ev_types as et', 'products.ev_type_id', '=', 'et.ev_type_id')
                ->where('products.hub_id', $hub->hub_id)
                ->whereNull('products.deleted_at')
                ->select(
                    'products.*',
                    'et.ev_type_name',
                    'et.slug as ev_type_slug',
                    'riders.customer_id',
                    'rider_orders.payment_status',
                    'rider_orders.status_id as statusid',
                    'rider_orders.assigned_date',
                    'rider_orders.cluster_manager',
                    'rider_orders.tl_name',
                    'rider_orders.client_name',
                    'rider_orders.client_address',
                    'rider_orders.slug as order_slug',
                    'riders.kyc_status',
                    DB::raw('CASE 
                            WHEN riders.kyc_status = 1 THEN "Verified" 
                            WHEN riders.kyc_status = 2 THEN "Pending" 
                            WHEN riders.kyc_status = 3 THEN "Red Flag"
                            ELSE "" 
                        END as kycStatus'),
                    DB::raw('CASE 
                            WHEN products.ev_category_id = 1 THEN "Two Wheeler" 
                            WHEN products.ev_category_id = 2 THEN "Three Wheeler" 
                            ELSE "" 
                        END as ev_category_name'),
                    DB::raw('CASE 
                            WHEN products.profile_category = 1 THEN "Corporate" 
                            WHEN products.profile_category = 2 THEN "Individual" 
                            WHEN products.profile_category = 3 THEN "Student" 
                            WHEN products.profile_category = 4 THEN "Vendor" 
                            ELSE "" 
                        END as profile_category_name')
                    )
                ->orderBy('products.created_at', 'DESC')
                ->paginate($perPage);
                $ev_types = EvType::orderBy('created_at', 'DESC')->get();
                $ev_categories = config('constants.EV_CATEGORIES');
                $rent_cycles = config('constants.RENT_CYCLE');
                $battery_types = config('constants.BATTERY_TYPE');
                $profile_categories = config('constants.PROFILE_CATEGORIES');
                $vehicleStatus = config('constants.VEHICLE_STATUS');
                $bike_types = config('constants.BIKE_TYPE');
                $count = Product::where('hub_id', $hub->hub_id)->whereNull('deleted_at')->count();
            }
            if ($param == 'employee') {
                $employees = User::select('users.*', 'roles.name as role_name')
                    ->where('users.hub_id', $hub->hub_id)
                    ->where('users.role_id', '!=', 0)
                    ->whereNull('users.deleted_at')
                    ->orderBy('users.created_at', 'DESC')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.role_id')
                    ->paginate($perPage);
                $roles = Role::whereNull('deleted_at')->get();
                $maxEmpId = User::select('emp_id')->orderBy('emp_id','DESC')->first();
                $hub->max_emp_id = $maxEmpId ? $maxEmpId->emp_id : 101;
                $count = User::where('hub_id', $hub->hub_id)->whereNull('deleted_at')->count();
            }
            if($param == 'accessories'){
                $hub_parts = HubPartAccessories::leftJoin('accessories', 'accessories.accessories_id', '=', 'hub_part_accessories.accessories_id')
                    ->leftJoin('hubs', 'hubs.hub_id', '=', 'hub_part_accessories.hub_id')
                    ->leftJoin('users', 'users.user_id', '=', 'hub_part_accessories.created_by')
                    ->where('hub_part_accessories.hub_id', $hub->hub_id)
                    ->whereNull('hub_part_accessories.deleted_at');

                $hub_parts = $hub_parts->select(
                    'hub_part_accessories.*',
                    'hubs.hubId',
                    'hubs.city',
                    'accessories.price',
                    DB::raw('CONCAT(users.first_name, " ", users.last_name) AS name'),
                    DB::raw('CASE 
                        WHEN accessories.accessories_category_id = 1 THEN "Helmet" 
                        WHEN accessories.accessories_category_id = 2 THEN "T-Shirt" 
                        WHEN accessories.accessories_category_id = 3 THEN "Mobile Holder"  
                    END as accessories')
                );
                $hub_parts = $hub_parts->orderBy('hub_part_accessories.created_at', 'DESC')->paginate($perPage);
                $count = HubPartAccessories::where('hub_id', $hub->hub_id)->count();
                $accessories_categories = config('constants.ACCESSORIES_CATEGORY');
            }
           

            if ($hub) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['hubs' => $hub, 'vehicles' => $vehicles, 'employees' => $employees, 'roles' => $roles,'rent_cycles' => $rent_cycles, 'ev_types' => $ev_types, 'ev_categories' => $ev_categories, 'battery_types' => $battery_types, 'profile_categories' => $profile_categories, 'vehicleStatus' => $vehicleStatus, 'bike_types' => $bike_types, 'count' => $count, 'hub_parts' => $hub_parts, 'accessories_categories' => $accessories_categories, 'vehicleCount'=> $vehicleCount, 'empCount'=>$empCount, 'accessoriesinHub' => $accessoriesinHub]);
            } else {
                
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['hubs' => [], 'vehicles' => [], 'employees' => [], 'roles' => [],'rent_cycles' => [], 'ev_types' => [], 'ev_categories' =>[], 'battery_types' => [], 'profile_categories' => [], 'bike_types' => [], 'count' => $count, 'hub_parts' => [], 'accessories_categories' => [],'vehicleCount' => 0, 'empCount' => 0, 'accessoriesinHub' => []]);
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
    Action    : Delete Hub
    --------------------------------------------------*/
    public function deleteHubBySlug($slug)
    {
        try {
            $deleteResult = Hub::where('slug', $slug);
            $result = $deleteResult->delete();
            Hub::where('slug', $slug)->update([
                "status_id" => 3,
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
    Action    : Hub Status changed
    --------------------------------------------------*/
    public function hubStatusChanged($request)
    {
        try {
            $statusId = Hub::where('slug', $request->slug)->select('status_id')->first();
            $result = Hub::where('slug', $request->slug)->update([
                "status_id" => $statusId->status_id == 1 ? 2 : 1,
            ]);
            if (!empty($result)) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.UPDATE'), $result);
            } else {
                return errorResponse(Response::HTTP_OK, Lang::get('messages.INSERT_ERROR'));
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
