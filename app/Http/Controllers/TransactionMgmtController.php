<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use App\Models\RiderTransactionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class TransactionMgmtController extends AdminAppController
{
    public $viewPath;
    protected $rider_transactions;

    public function __construct()
    {
        $this->rider_transactions = new RiderTransactionHistory();
        $this->viewPath = "admin/transaction";
    }

    public function index(Request $request)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('view', $permission)) {
                $transaction = $this->rider_transactions->getTransactionHistory($request);
                $transactions = $transaction['result']['transactions'];
                $count = $transaction['result']['count'];
                return view($this->viewPath . '/index', compact('permission', 'transactions','count'));
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
