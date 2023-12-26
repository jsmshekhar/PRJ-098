<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use App\Models\User;

class HomeController extends AdminAppController
{
    public $viewPath;

    public function __construct()
    {
        $this->viewPath = "admin/home";
    }


    public function index()
    {
        $permission = User::getPermissions();
        return view($this->viewPath . '/dashboard', compact('permission'));
    }
}
