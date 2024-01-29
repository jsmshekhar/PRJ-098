<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use App\Models\PhonePay;
use App\Models\ReturnExchange;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;

class RefundMgmtController extends AdminAppController
{
    public $viewPath;
    public function __construct()
    {
        $this->viewPath = "admin/refund";
    }


    public function index(Request $request)
    {

        try {
            // $res = PhonePay::refundAmmount();
            $permission = User::getPermissions();
            if (Gate::allows('view_inventry', $permission)) {
                $auth = Auth::user();
                $refunds = ReturnExchange::leftJoin('riders', 'return_exchanges.rider_id', 'riders.rider_id')
                    ->leftJoin('hubs', 'hubs.hub_id', 'return_exchanges.hub_id')
                    ->leftJoin('users', 'users.user_id', 'return_exchanges.created_by')
                    ->select(
                        'return_exchanges.refund_ammount',
                        'return_exchanges.refund_date',
                        'return_exchanges.note',
                        'riders.customer_id',
                        'riders.name as rider_name',
                        'riders.phone as rider_phone',
                        'hubs.hubId',
                        'users.phone as mng_phone',
                        DB::raw("CONCAT(users.first_name, ' ', users.last_name) as mng_name"),
                        DB::raw('CASE
                            WHEN return_exchanges.status_id = 1 THEN "Resolved"
                            WHEN return_exchanges.status_id = 2 THEN "Pending"
                            ELSE ""
                        END as status'),
                    );

                if (isset($request->is_search) && $request->is_search == 1) {
                    if (isset($request->hub_id) && !empty($request->hub_id)) {
                        $refunds = $refunds->where('hubs.transaction_id', 'LIKE', "%{$request->hubId}%");
                    }
                    if (isset($request->mng_name) && !empty($request->mng_name)) {
                        $refunds = $refunds->where('users.first_name', 'LIKE', $request->mng_name);
                    }
                    if (isset($request->rider_id) && !empty($request->rider_id)) {
                        $refunds = $refunds->where('riders.customer_id', 'LIKE', $request->rider_id);
                    }
                    if (isset($request->rd_name) && !empty($request->rd_name)) {
                        $refunds = $refunds->where('riders.name', 'LIKE', $request->rd_name);
                    }
                    if (isset($request->date) && !empty($request->date)) {
                        $refunds = $refunds->whereDate('return_exchanges.refund_date', $request->date);
                    }
                    if (isset($request->status) && !empty($request->status)) {
                        $refunds = $refunds->where('return_exchanges.status_id', $request->status);
                    }

                }
                $refunds = $refunds->where(function ($query) {
                    $query->where(DB::raw("DATEDIFF(return_exchanges.return_date, return_exchanges.assigned_date) / 30"), '>=', 3);
                });
                $refunds = $refunds->where('return_exchanges.request_for', 1)->orderBy('return_exchanges.created_at', 'DESC')->paginate(20);

                $count = ReturnExchange::get();
                $count = count($count);
                $refundStatus = ['1' => 'Resolved', '2' => 'Pending'];
                return view($this->viewPath . '/index', compact('refunds', 'count', 'refundStatus', 'permission'));
            }else {
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

}
