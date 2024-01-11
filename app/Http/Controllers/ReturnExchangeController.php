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
use App\Models\MediaFile;
use App\Models\Product;
use App\Models\ReturnExchange;
use App\Models\RiderOrder;

class ReturnExchangeController extends AdminAppController
{
    protected $model;
    public $viewPath;

    public function __construct()
    {
        $this->model = new ReturnExchange();
        $this->viewPath = "admin/orders";
    }

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : Order List
    --------------------------------------------------*/
    public function index(Request $request)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('view', $permission)) {
                $perPage = env('PER_PAGE');
                $permission = User::getPermissions();
                $records = $this->model::with(['rider', 'product', 'hub', 'order'])->whereNull('deleted_at');
                $records = $records->orderBy('created_at', 'DESC')->paginate($perPage);
                return view($this->viewPath . '/return-exchange', compact('records', 'permission'));
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
}
