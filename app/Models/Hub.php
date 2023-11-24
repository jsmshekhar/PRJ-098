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
            $hubs = Hub::where('user_id', $auth->user_id)->whereNull('deleted_at');
            if (isset($request->is_search) && $request->is_search == 1) {
                if (isset($request->hub_id) && !empty($request->hub_id)) {
                    $hubs = $hubs->where('hubId', $request->hub_id);
                }
                if (isset($request->city) && !empty($request->city)) {
                    $hubs = $hubs->where('city', $request->city);
                }
                if (isset($request->hub_capacity) && !empty($request->hub_capacity)) {
                    $hubs = $hubs->where('hub_limit', $request->hub_capacity);
                }
                if (isset($request->vehicle) && !empty($request->vehicle)) {
                    // $hubs = $hubs->where('hub_limit', $request->vehicle);
                }
            }

            $hubs = $hubs->orderBy('created_at', 'DESC')->paginate($perPage);
            $lastHub = Hub::orderBy('hub_id', 'DESC')->first();
            $hubId = "NO101";
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
            $hub = Hub::where('slug', $slug)->whereNull('deleted_at')->first();
            if($param == 'vehicle'){
                $vehicles = Product::join('product_categories as pc', 'products.product_category_id', '=', 'pc.product_category_id')
                ->join('ev_types as et', 'products.ev_type_id', '=', 'et.ev_type_id')
                ->where('products.hub_id', $hub->hub_id)
                ->whereNull('products.deleted_at')
                ->where('products.product_category_id', 1)
                ->select(
                    'products.*',
                    'pc.product_category_name',
                    'pc.slug as pc_slug',
                    'et.ev_type_name',
                    'et.slug as ev_type_slug',
                    DB::raw("CASE 
                        WHEN products.ev_category='two_wheeler' THEN '" . config('constants.EV_CATEGORIES.two_wheeler') . "' 
                        WHEN products.ev_category='three_wheeler' THEN '" . config('constants.EV_CATEGORIES.three_wheeler') . "' 
                        ELSE '' 
                    END as ev_category_name"),
                    DB::raw("CASE 
                        WHEN products.profile_category='individual' THEN '" . config('constants.PROFILE_CATEGORIES.individual') . "' 
                        WHEN products.profile_category='corporate' THEN '" . config('constants.PROFILE_CATEGORIES.corporate') . "' 
                        ELSE '' 
                    END as profile_category_name")
                )
                ->paginate($perPage);
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
            }
            if ($hub) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['hubs' => $hub, 'vehicles' => $vehicles, 'employees' => $employees, 'roles' => $roles]);
            } else {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['hubs' => [], 'vehicles' => [], 'employees' => [], 'roles' => []]);
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
