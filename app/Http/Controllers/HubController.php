<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Hub;
use App\Models\User;
use App\Http\Controllers\AdminAppController;


class HubController extends AdminAppController
{
    protected $hub;

    public function __construct()
    {
        $this->hub = new Hub();
    }

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Get hubs
    --------------------------------------------------*/
    public function getHubs(Request $request)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('hub_list', $permission)) {
                $hub = $this->hub->getHubs($request);
                $hubs = $hub['result']['hubs'];
                $hubId = $hub['result']['hubId'];
                return view('admin.hub.create_hub', compact('hubs', 'hubId', 'permission'));
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
    Action    : View Hub
    --------------------------------------------------*/
    public function viewHub(Request $request, $slug, $param)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('hub_view', $permission)) {
                $hubs = $this->hub->viewHub($request, $slug, $param);
                // /dd($hubs);
                $hub = $hubs['result']['hubs'];
                $vehicles = $hubs['result']['vehicles'];
                $employees = $hubs['result']['employees'];
                $roles = $hubs['result']['roles'];
                $rent_cycles = $hubs['result']['rent_cycles'];
                $ev_types = $hubs['result']['ev_types'];
                $ev_categories = $hubs['result']['ev_categories'];
                $battery_types = $hubs['result']['battery_types'];
                $profile_categories = $hubs['result']['profile_categories'];
                $vehicleStatus = $hubs['result']['vehicleStatus'];
                $bike_types = $hubs['result']['bike_types'];
                $count = $hubs['result']['count'];
                $hub_parts = $hubs['result']['hub_parts'];
                $empCount = $hubs['result']['empCount'];
                $vehicleCount = $hubs['result']['vehicleCount'];
                $accessoriesinHub = $hubs['result']['accessoriesinHub'];
                $accessories_categories = $hubs['result']['accessories_categories'];
                return view('admin.hub.view_hub', compact('hub', 'vehicles', 'employees', 'roles','rent_cycles', 'ev_types', 'ev_categories', 'battery_types', 'profile_categories', 'vehicleStatus', 'permission', 'bike_types', 'count', 'hub_parts', 'accessories_categories','empCount', 'vehicleCount', 'accessoriesinHub'));
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
    Action    : Add Hub
    --------------------------------------------------*/
    public function addUpdateHub(Request $request)
    {
        try {
            $hubId = !empty($request->hubId) ? $request->hubId : "";
            $city = !empty($request->city) ? $request->city : "";
            $state = !empty($request->state) ? $request->state : "";
            $country = !empty($request->country) ? $request->country : "";
            $address1 = !empty($request->address1) ? $request->address1 : "";
            $address2 = !empty($request->address2) ? $request->address2 : "";
            $full_address = !empty($request->full_address) ? $request->full_address : "";
            $hub_limit = !empty($request->hub_limit) ? $request->hub_limit : null;
            $zip_code = !empty($request->zip_code) ? $request->zip_code : "";
            $slug = !empty($request->slug) ? $request->slug : "";
            $auth = Auth::user();
            if (!empty($request->slug)) {
                $hubId = Hub::where('slug', $slug)->update([
                    "city" => $city,
                    "state" => $state,
                    "country" => $country,
                    "address_1" => $address1,
                    "address_2" => $address2,
                    "full_address" => $full_address,
                    "hub_limit" => $hub_limit,
                    "zip_code" => $zip_code,
                ]);
            } else {
                $slug = slug();
                $hubId = Hub::insertGetId([
                    "slug" => $slug,
                    "city" => $city,
                    "state" => $state,
                    "country" => $country,
                    "address_1" => $address1,
                    "address_2" => $address2,
                    "full_address" => $full_address,
                    "hub_limit" => $hub_limit,
                    "zip_code" => $zip_code,
                    "hubId" => $hubId,
                    "user_id" => $auth->user_id,
                    "user_slug" => $auth->slug,
                    "created_by" => $auth->user_id,
                ]);
            }
            if ($hubId) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'url' => url('/distributed-hubs'),
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
    Action    : Delete Hub
    --------------------------------------------------*/
    public function deleteHub($slug)
    {
        try {
            $deleteResult = $this->hub->deleteHubBySlug($slug);
            if (!empty($deleteResult)) {
                return redirect()->back()->with('message', Lang::get('messages.DELETE'));
            } else {
                return redirect()->back()->with('message', Lang::get('messages.DELETE_ERROR'));
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
    Action    : Hub Status Changed
    --------------------------------------------------*/
    public function hubStatusChanged(Request $request)
    {
        try {
            $hubStatus = $this->hub->hubStatusChanged($request);
            $status = [
                'status' => Response::HTTP_OK,
                'url' => url('/distributed-hubs'),
                'message' => Lang::get('messages.UPDATE'),
            ];
            return response()->json($status);
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
