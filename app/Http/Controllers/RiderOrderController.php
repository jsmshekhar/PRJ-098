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
use App\Models\Product;
use App\Models\RiderOrder;

class RiderOrderController extends AdminAppController
{
    protected $model;
    public $viewPath;

    public function __construct()
    {
        $this->model = new RiderOrder();
        $this->viewPath = "admin/orders";
    }

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : Order List
    --------------------------------------------------*/
    public function index(Request $request)
    {
        try {
            $perPage = env('PER_PAGE');
            $permission = User::getPermissions();
            $orders = $this->model::with('rider')->whereNull('deleted_at');
            $orders = $orders->where('status_id', config('constants.ORDER_STATUS.PENDING'));
            $orders = $orders->orderBy('created_at', 'DESC')->paginate($perPage);
            $evList = Product::where('status_id', 1)->whereNull('deleted_at')->pluck('title', 'slug')->toArray();
            return view($this->viewPath . '/index', compact('orders', 'permission', 'evList'));
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
    Developer : Chandra Shekhar
    Action    : Assign An Evs
    --------------------------------------------------*/
    public function assignEv(Request $request)
    {
        try {
            $result = $this->model->assignEv($request);
            $result = json_encode($result);
            $result = json_decode($result, true);
            $response = [
                'status' => $result['original']['status'],
                'message' => $result['original']['message'],
            ];
            return response()->json($response);
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
