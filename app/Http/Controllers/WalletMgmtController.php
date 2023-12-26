<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use App\Models\User;

class WalletMgmtController extends AdminAppController
{
    public $viewPath;

    public function __construct()
    {
        $this->viewPath = "admin/wallet";
    }


    public function index()
    {
        $permission = User::getPermissions();
        return view($this->viewPath . '/index', compact('permission'));
    }
}
