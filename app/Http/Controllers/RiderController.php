<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Models\Rider;
use App\Models\User;
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
                $riders = Rider::whereNull('deleted_at');
                if (isset($request->is_search) && $request->is_search == 1) {
                    if (isset($request->customer_id) && !empty($request->customer_id)) {
                        $riders = $riders->where('slug', $request->customer_id);
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
                return view($this->viewPath . '/rider_view', compact('rider', 'walletBalence', 'permission'));
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
}
