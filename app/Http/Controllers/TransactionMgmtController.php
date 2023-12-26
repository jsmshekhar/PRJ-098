<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use App\Models\User;

class TransactionMgmtController extends AdminAppController
{
    public $viewPath;

    public function __construct()
    {
        $this->viewPath = "admin/transaction";
    }


    public function index()
    {
        $permission = User::getPermissions();
        return view($this->viewPath . '/index', compact('permission'));
    }
}
