<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use App\Models\ApiModel;
use App\Models\Rider;
use App\Models\RiderOrder;
use App\Models\RiderOrderPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;

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
                if (!is_null($riderEv)) {
                    $riderEv->last_ev = null;

                    $lastRec = DB::table('rider_orders AS ro')
                        ->leftJoin('products', 'products.product_id', '=', 'ro.mapped_vehicle_id')
                        ->leftJoin('hubs', 'hubs.hub_id', '=', 'products.hub_id')
                        ->where('ro.rider_id', $riderId)
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
                }
                $transactions = $rider->transactions()->paginate(15);

                $currentOrder = ApiModel::getCurrentOrderDetails($riderId);
                $orderDetails = $paymentDetails = null;
                if (isset($currentOrder['order_code']) && !empty($currentOrder['order_code'])) {
                    $orderCode = $currentOrder['order_code'];
                    $orderDetails = RiderOrder::where('slug', $orderCode)->whereNull('deleted_at')->first();
                    $orderId = $orderDetails->order_id;

                    $payments = RiderOrderPayment::selectRaw('*')->where(['rider_id' => $riderId, 'order_id' => $orderId])->orderBy('rider_order_payment_id', 'DESC')->first();
                    $lastDate = !is_null(($payments)) ? dateTimeFormat($payments->to_date) : '';
                    $rentCycle = $orderDetails->subscription_days;
                    $paymentDetails = [
                        'order_code' => $orderCode,
                        'basic_rent' => $orderDetails->product_price,
                        'payble_rent' => (string) ($rentCycle * $orderDetails->product_price),
                        'last_date' => $lastDate,
                        'rent_cycle' => $rentCycle,
                        'extra_distance_charge' => 0,
                        'penalty_of_extra_days' => 0,
                    ];
                }
                return view($this->viewPath . '/rider_view', compact('rider', 'walletBalence', 'permission', 'kycStatus', 'riderEv', 'transactions', 'orderDetails', 'paymentDetails'));
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

    /*--------------------------------------------------
    Developer : Chandra Shehar
    Action    : Get current payment details
    --------------------------------------------------*/
    public function payCodRent(Request $request)
    {
        try {
            $rider = Rider::where('slug', $request->rider_slug)->whereNull('deleted_at')->first();
            if (!is_null($rider)) {
                $orderCode = $request->order_code ?? null;
                $orderDetail = RiderOrder::where('slug', $orderCode)->whereNull('deleted_at')->first();
                if (!is_null($orderDetail)) {
                    $orderId = $orderDetail->order_id;
                    $riderId = $rider->rider_id;
                    $transactionMode = $orderDetail->transaction_mode ?? null;
                    if ($transactionMode == 4) {
                        $mappedVehicleId = $orderDetail->mapped_vehicle_id;
                        $rentCycle = (int) $orderDetail->subscription_days;

                        $payments = RiderOrderPayment::selectRaw('*')->where(['rider_id' => $riderId, 'order_id' => $orderId])->orderBy('rider_order_payment_id', 'DESC')->first();

                        $fromDate = !is_null($payments) ? Carbon::parse($payments->to_date)->addDay() : '';
                        $toDate = !is_null($payments) ? Carbon::parse($payments->to_date)->addDay($rentCycle) : '';

                        $riderOrderPayments = [
                            'slug' => slug(),
                            'order_id' => $orderId,
                            'rider_id' => $riderId,
                            'mapped_vehicle_id' => $mappedVehicleId,
                            'from_date' => $fromDate,
                            'to_date' => $toDate,
                            'status_id' => 1,
                        ];
                        RiderOrderPayment::insert($riderOrderPayments);

                        $orderTransaction = [
                            "rider_id" => $riderId,
                            "order_id" => $orderId,
                            "slug" => slug(),
                            "order_slug" => $orderCode,
                            "transaction_ammount" => $request->paying_ammount ?? 0,
                            "transaction_type" => 1, //Credited to our portal
                            'transaction_mode' => $transactionMode,

                            'status_id' => 5, // COD
                            'payment_status' => 5, // COD
                            'merchant_transaction_id' => null,
                            'transaction_id' => "COD_" . slug(),
                            'transaction_payload' => null,
                            'transaction_notes' => 'Cash - Payment done from portal (Rent Pay)',
                            "created_by" => $riderId,
                            "created_at" => NOW(),
                        ];
                        $transactionHistoryId = DB::table('rider_transaction_histories')->insertGetId($orderTransaction);

                        $collectedAmmountDetails = [
                            "slug" => slug(),
                            "rider_id" => $riderId,
                            "order_id" => $orderId,
                            "transaction_id" => $transactionHistoryId,
                            "user_id" => Auth::id(),
                            "ammount" => $request->paying_ammount ?? 0,
                            'status_id' => 1,
                            "created_by" => Auth::id(),
                            "created_at" => NOW(),
                        ];
                        DB::table('transaction_collected_ammounts')->insertGetId($collectedAmmountDetails);
                    }

                    $status = [
                        'status' => Response::HTTP_OK,
                        'message' => Lang::get('messages.RENT_PAID'),
                        'result' => [],
                    ];
                    return response()->json($status);
                }

            }
            $status = [
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => Lang::get('messages.NOT_FOUND'),
                'result' => [],
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
