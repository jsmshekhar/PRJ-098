<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;

class LiveTrackingController extends AdminAppController
{
    public $viewPath;

    public function __construct()
    {
        $this->viewPath = "admin/tracking";
    }


    public function index()
    {
        return view($this->viewPath . '/index');
    }
}
