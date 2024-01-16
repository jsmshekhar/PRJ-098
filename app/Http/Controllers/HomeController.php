<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        $evstatus2W = Product::where('ev_category_id', 1)->select(
            DB::raw('COUNT(CASE WHEN status_id = 1 THEN 1 END) as functional'),
            DB::raw('COUNT(CASE WHEN status_id = 3 THEN 1 END) as non_functional'),
            DB::raw('COUNT(CASE WHEN ev_status = 1 THEN 1 END) as mobilized'),
            DB::raw('COUNT(CASE WHEN ev_status = 2 THEN 1 END) as immobilized')
        )->first();
        $evstatus3W = Product::where('ev_category_id', 2)->select(
            DB::raw('COUNT(CASE WHEN status_id = 1 THEN 1 END) as functional'),
            DB::raw('COUNT(CASE WHEN status_id = 3 THEN 1 END) as non_functional'),
            DB::raw('COUNT(CASE WHEN ev_status = 1 THEN 1 END) as mobilized'),
            DB::raw('COUNT(CASE WHEN ev_status = 2 THEN 1 END) as immobilized')
        )->first();
        $evCount2W = Product::whereNull('deleted_at')->where('ev_category_id', 1)->count();
        $evCount3W = Product::whereNull('deleted_at')->where('ev_category_id', 2)->count();
        return view($this->viewPath . '/dashboard', compact('permission', 'evstatus2W', 'evCount2W', 'evstatus3W', 'evCount3W'));
    }
}
