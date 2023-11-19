<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;

class HomeController extends AdminAppController
{
    public $viewPath;

    public function __construct()
    {
        $this->viewPath = "admin/home";
    }


    public function index()
    {
        return view($this->viewPath . '/dashboard');
    }
}
