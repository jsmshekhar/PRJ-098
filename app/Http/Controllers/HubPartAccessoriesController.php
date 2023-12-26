<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use App\Models\Accessories;
use App\Models\Hub;
use App\Models\HubPartAccessories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class HubPartAccessoriesController extends AdminAppController
{
    protected $hub_part_accessories;

    public function __construct()
    {
        $this->hub_part_accessories = new HubPartAccessories();
    }

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Get hub_part_accessories
    --------------------------------------------------*/
    public function getHubPartAccessories(Request $request)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('view', $permission)) {
                $auth = Auth::user();
                $perPage = env('PER_PAGE');
                if (isset($request->per_page) && $request->per_page > 0) {
                    $perPage = $request->per_page;
                }
                if ($auth->hub_id == null || $auth->hub_id == "") {
                    $hub_parts = HubPartAccessories::leftJoin('accessories', 'accessories.accessories_id', '=', 'hub_part_accessories.accessories_id')
                    ->leftJoin('hubs', 'hubs.hub_id', '=', 'hub_part_accessories.hub_id')
                    ->leftJoin('users', 'users.user_id', '=', 'hub_part_accessories.created_by')
                    ->whereNull('hub_part_accessories.deleted_at');
                } else {
                    $hub_parts = HubPartAccessories::leftJoin('accessories', 'accessories.accessories_id', '=', 'hub_part_accessories.accessories_id')
                    ->leftJoin('hubs', 'hubs.hub_id', '=', 'hub_part_accessories.hub_id')
                    ->leftJoin('users', 'users.user_id', '=', 'hub_part_accessories.created_by')
                    ->where('hub_part_accessories.hub_id', $auth->hub_id)
                    ->whereNull('hub_part_accessories.deleted_at');
                }

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
                if (isset($request->is_search) && $request->is_search == 1) {
                    if (isset($request->hubid) && !empty($request->hubid)) {
                        $hub_parts = $hub_parts->where('hubs.hubId', 'LIKE', "%{$request->hubid}%");
                    }
                    if (isset($request->hubid) && !empty($request->hubid)) {
                        $hub_parts = $hub_parts->where('hubs.city', 'LIKE', "%{$request->hub_loc}%");
                    }
                    if (isset($request->fname) && !empty($request->fname)) {
                        $hub_parts = $hub_parts->where('users.first_name', 'LIKE', "%{$request->fname}%");
                    }
                    if (isset($request->lname) && !empty($request->lname)) {
                        $hub_parts = $hub_parts->where('users.last_name', 'LIKE', "%{$request->lname}%");
                    }
                    if (isset($request->aci) && !empty($request->aci)) {
                        $hub_parts = $hub_parts->where('hub_part_accessories.accessories_category_id', $request->aci);
                    }
                    if (isset($request->status) && !empty($request->status)) {
                        $hub_parts = $hub_parts->where('hub_part_accessories.status_id', $request->status);
                    }
                }
                $hub_parts = $hub_parts->orderBy('hub_part_accessories.created_at', 'DESC')->paginate($perPage);
                $count = 0;
                if (count($hub_parts) > 0) {
                    if ($auth->hub_id == null || $auth->hub_id == "") {
                        $count = HubPartAccessories::count();
                    } else {
                        $count = HubPartAccessories::where('hub_id', $auth->hub_id)->count();
                    }
                }
                $accessories_categories = config('constants.ACCESSORIES_CATEGORY');
           
                return view('admin.hub_part_accessories.index', compact('hub_parts', 'permission', 'accessories_categories','count'));
            } else {
                return view('admin.401.401');
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
    Action    : Raise e Request Hub Part Accessories
    --------------------------------------------------*/
    public function addRequestHubPart(Request $request)
    {
        try {
            $auth = Auth::user();
            $requiredFields = [
                'accessories_category_id' => 'required',
                'requested_qty' => 'required',
            ];
            if (!$this->checkValidation($request, $requiredFields)) {
                $msg = $this->errorMessage;
                $msg = $msg['errorDetail'];
                $msg = json_encode($msg[0]);
                $msg = json_decode($msg, true);
                $errorText = json_encode($msg['errorMessage'][0]);
                $status = [
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'url' => "",
                    'message' => $errorText,
                ];
                dd($status);
                return response()->json($status);
            }
            else{
                $accessories_category_id = !empty($request->accessories_category_id) ? $request->accessories_category_id : null;
                $requested_qty = !empty($request->requested_qty) ? $request->requested_qty : null;
                $requested_remark = !empty($request->requested_remark) ? $request->requested_remark : "";
                $accessories = Accessories::where('accessories_category_id', $accessories_category_id)->select('title', 'price','accessories_id')->first();
                $slug = slug();
                $hub_part_accessories = HubPartAccessories::insertGetId([
                    "slug" => $slug,
                    "requested_qty" => $requested_qty,
                    "accessories_category_id" => $accessories_category_id,
                    "requested_remark" => $requested_remark,
                    "requested_date" => date('Y-m-d H:i:s'),
                    "hub_id" => $auth->hub_id,
                    "accessories_id" => $accessories->accessories_id,
                    "accessories_title" => $accessories->title,
                    "accessories_price" => $accessories->price * $requested_qty,
                    "created_by" => $auth->user_id,
                ]);
                
                if ($hub_part_accessories) {
                    return redirect()->back()->with('message', Lang::get('messages.INSERT')); 
                } else {
                    return redirect()->back()->with('message', Lang::get('messages.INSERT_ERROR')); 
                }

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
    Action    : Assign Hub Part Accessories
    --------------------------------------------------*/
    public function updateAssignedAccessories(Request $request)
    {
        try {
            $auth = Auth::user();
            
            $slug = !empty($request->slug) ? $request->slug : null;
            $assign_qty = !empty($request->assign_qty) ? $request->assign_qty : null;
            $assigned_cost = !empty($request->assigned_cost) ? $request->assigned_cost : null;
            $assigned_remark = !empty($request->assigned_remark) ? $request->assigned_remark : "";
            
            $hub_part_accessories = HubPartAccessories::where('slug', $slug)->update([
                "assigned_qty" => $assign_qty,
                "assigned_price" => $assigned_cost,
                "assigned_remark" => $assigned_remark,
                "status_id" => 3, //completed
                "assign_date" => date('Y-m-d H:i:s'),
                "updated_by" => $auth->user_id,
            ]);

            if ($hub_part_accessories) {
                return redirect()->back()->with('message', Lang::get('messages.INSERT'));
            } else {
                return redirect()->back()->with('message', Lang::get('messages.INSERT_ERROR'));
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
    Action    : Hub part reject
    --------------------------------------------------*/
    public function rejectRequestAccessories($slug)
    {
        try {
            HubPartAccessories::where('slug', $slug)->update([
                "status_id" => 4,  //rejct status 4
            ]);
            return redirect()->back();
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
