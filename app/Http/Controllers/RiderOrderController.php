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
    Developer : Raj Kumar
    Action    : Get Riders
    --------------------------------------------------*/
    public function index(Request $request)
    {
        try {
            $perPage = env('PER_PAGE');
            $permission = User::getPermissions();
            $orders = $this->model::with('rider')->whereNull('deleted_at');
            $orders = $orders->orderBy('created_at', 'DESC')->paginate($perPage);
            return view($this->viewPath . '/index', compact('orders', 'permission'));
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
