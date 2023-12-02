<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;

class WalletMgmtController extends AdminAppController
{
    public $viewPath;

    public function __construct()
    {
        $this->viewPath = "admin/wallet";
    }


    public function index()
    {
        return view($this->viewPath . '/index');
    }
}
