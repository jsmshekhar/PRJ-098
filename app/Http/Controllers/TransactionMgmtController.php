<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;

class TransactionMgmtController extends AdminAppController
{
    public $viewPath;

    public function __construct()
    {
        $this->viewPath = "admin/transaction";
    }


    public function index()
    {
        return view($this->viewPath . '/index');
    }
}
