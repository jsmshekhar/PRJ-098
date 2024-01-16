<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use App\Models\Product;
use App\Models\Rider;
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

        $ridersData = Rider::where('status_id', 1)->whereNull('deleted_at')
        ->select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('CASE 
                WHEN profile_type = 1 THEN "Corporate" 
                WHEN profile_type = 2 THEN "Individual" 
                WHEN profile_type = 3 THEN "Student" 
                WHEN profile_type = 4 THEN "Vendor" 
            END as profile_type'),
            DB::raw('count(*) as count')
        )
        ->groupBy('year', 'profile_type')
        ->get()->toArray();

        $ridersArray = [
            ['Year', 'Corporate', 'Individual', 'Student', 'Vendor']
        ];

        foreach ($ridersData as $count) {
            $yearIndex = array_search((string)$count['year'], array_column($ridersArray, 0));

            if ($yearIndex === false) {
                $ridersArray[] = [
                    (string)$count['year'],
                    $count['profile_type'] == 'Corporate' ? $count['count'] : 0,
                    $count['profile_type'] == 'Individual' ? $count['count'] : 0,
                    $count['profile_type'] == 'Student' ? $count['count'] : 0,
                    $count['profile_type'] == 'Vendor' ? $count['count'] : 0,
                ];
            } else {
                $ridersArray[$yearIndex][array_search($count['profile_type'], $ridersArray[0])] = $count['count'];
            }
        }
        //dd($ridersArray);
        return view($this->viewPath . '/dashboard', compact('permission', 'evstatus2W', 'evCount2W', 'evstatus3W', 'evCount3W', 'ridersArray'));
    }
}
