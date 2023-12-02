<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;

class RefundMgmtController extends AdminAppController
{
    public $viewPath;

    public function __construct()
    {
        $this->viewPath = "admin/refund";
    }


    public function index()
    {
        return view($this->viewPath . '/index');
    }
}
