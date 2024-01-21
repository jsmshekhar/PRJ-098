<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rider;
use App\Models\RiderOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;
use App\Http\Controllers\AdminAppController;

class RiderController extends AdminAppController
{
    protected $rider;
    public $viewPath;

    public function __construct()
    {
        $this->rider = new Rider();
        $this->viewPath = "admin/rider";
    }

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Get Riders
    --------------------------------------------------*/
    public function getRiders(Request $request)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('enable_disable_customer', $permission)) {
                $perPage = env('PER_PAGE');
                if (isset($request->per_page) && $request->per_page > 0) {
                    $perPage = $request->per_page;
                }
                $riders = Rider::whereNull('deleted_at')->with('order');
                if (isset($request->is_search) && $request->is_search == 1) {
                    if (isset($request->customer_id) && !empty($request->customer_id)) {
                        $riders = $riders->where('customer_id', $request->customer_id);
                    }
                    if (isset($request->name) && !empty($request->name)) {
                        $riders = $riders->where('name', 'like', '%' . $request->name . '%');
                    }
                    if (isset($request->email) && !empty($request->email)) {
                        $riders = $riders->where('email', 'like', '%' . $request->email . '%');
                    }
                    if (isset($request->phone) && !empty($request->phone)) {
                        $riders = $riders->where('phone', $request->phone);
                    }
                    if (isset($request->joining_date) && !empty($request->joining_date)) {
                        $riders = $riders->whereDate('created_at', $request->joining_date);
                    }
                }
                $riders = $riders->orderBy('created_at', 'DESC')->paginate($perPage);
                return view($this->viewPath . '/rider_list', compact('riders', 'permission'));
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
    Action    : Get Riders
    --------------------------------------------------*/
    public function viewRider(Request $request, $slug)
    {
        try {
            $permission = User::getPermissions();
            $kycStatus = ['1' => 'Verified', '2' => 'Pending', '3' => 'Red Flag'];
            $rider = Rider::with(['bankDetail', 'documents', 'transactions', 'complaints'])->where('slug', $slug)->whereNull('deleted_at')->first();
            if (!is_null($rider)) {
                $walletBalence = 0;
                $riderId = $rider->rider_id;
                $walletValue = DB::table('rider_wallets')
                    ->selectRaw('SUM(CASE WHEN status_id = 1 THEN ammount ELSE 0 END) AS total_credits')
                    ->selectRaw('SUM(CASE WHEN status_id = 2 THEN ammount ELSE 0 END) AS total_debits')
                    ->where('rider_id', $riderId)
                    ->whereNull('deleted_at')
                    ->first();
                if (!is_null($walletValue)) {
                    $walletBalence = $walletValue->total_credits - $walletValue->total_debits;
                }

                // Query 1
                $riderEv = DB::table('rider_orders as ro')
                    ->select('ro.subscription_days as rent_cycle', 'products.ev_number', 'hubs.hubId')
                    ->leftJoin('products', 'products.product_id', '=', 'ro.mapped_vehicle_id')
                    ->leftJoin('hubs', 'hubs.hub_id', '=', 'products.hub_id')
                    ->where('ro.rider_id', $riderId)
                    ->orderByDesc('ro.order_id')
                    ->first();
                $riderEv->last_ev = null;

                $lastRec = DB::table('rider_orders AS ro')
                    ->leftJoin('products', 'products.product_id', '=', 'ro.mapped_vehicle_id')
                    ->leftJoin('hubs', 'hubs.hub_id', '=', 'products.hub_id')
                    ->orderBy('ro.order_id', 'desc')->skip(1)->first();
                if (!empty(($lastRec))) {
                    $riderEv->last_ev = $lastRec->ev_number;
                }

                $subscriptionStatus = "Inactive";
                $subscription = DB::table('rider_order_payments')
                    ->where('to_date', '>', now())
                    ->where('rider_id', $riderId)
                    ->orderByDesc('rider_order_payment_id')
                    ->first();
                if (!empty($subscription)) {
                    $subscriptionStatus = "Active";
                }
                $riderEv->subscriptionStatus = $subscriptionStatus;

                return view($this->viewPath . '/rider_view', compact('rider', 'walletBalence', 'permission', 'kycStatus', 'riderEv'));
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
    Action    : Rider Status Changed
    --------------------------------------------------*/
    public function riderStatusChanged(Request $request)
    {
        try {
            $statusId = Rider::where('slug', $request->slug)->select('status_id')->first();
            $updateResult = Rider::where('slug', $request->slug)->update([
                "status_id" => $statusId->status_id == 1 ? 3 : 1,
            ]);
            if ($updateResult) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'url' => url('/customer-management'),
                    'message' => Lang::get('messages.UPDATE'),
                ];
            } else {
                $status = [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'url' => "",
                    'message' => Lang::get('messages.INSERT_ERROR'),
                ];
            }
            return response()->json($status);
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
    Developer : Chandra Shehar
    Action    : Rider Kyc Status Changed
    --------------------------------------------------*/
    public function changeKycStatus(Request $request)
    {
        try {
            $updateResult = Rider::where('slug', $request->rider_slug)->update([
                "kyc_status" => $request->kyc_status ?? 1,
            ]);
            if ($updateResult) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'message' => Lang::get('messages.UPDATE'),
                ];
            } else {
                $status = [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => Lang::get('messages.UPDATE_ERROR'),
                ];
            }
            return response()->json($status);
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
    Developer : Chandra Shehar
    Action    : Update Rider Details
    --------------------------------------------------*/
    public function updateRiderDetails(Request $request)
    {
        try {
            $rider = Rider::where('slug', $request->rider_slug)->whereNull('deleted_at')->first();
            if (!is_null($rider)) {
                $updateResult = Rider::where('slug', $request->rider_slug)->update([
                    "current_address" => $request->current_address ?? $request->current_address,
                    "permanent_address" => $request->permanent_address ?? $request->permanent_address,
                ]);
                if ($updateResult) {
                    $status = [
                        'status' => Response::HTTP_OK,
                        'message' => Lang::get('messages.UPDATE'),
                    ];
                    return response()->json($status);
                }
            }
            $status = [
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => Lang::get('messages.UPDATE_ERROR'),
            ];
            return response()->json($status);
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
