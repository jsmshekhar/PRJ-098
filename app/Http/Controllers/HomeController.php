<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use App\Models\Product;
use App\Models\Rider;
use App\Models\RiderOrderPayment;
use App\Models\RiderTransactionHistory;
use App\Models\User;
use Carbon\Carbon;
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
        $evCount2W = Product::whereNull('deleted_at')->where('ev_category_id', 1)->where('status_id', '!=', 2)->count();
        $evCount3W = Product::whereNull('deleted_at')->where('ev_category_id', 2)->where('status_id', '!=', 2)->count();

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
        //Total Revenue
        $sumOfcredit = RiderTransactionHistory::where('transaction_type',1)->sum('transaction_ammount');
        $sumOfdebit = RiderTransactionHistory::where('transaction_type', 2)->sum('transaction_ammount');
        $totalRevenue =  $sumOfcredit - $sumOfdebit;
       
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $recievedRevenue = RiderTransactionHistory::where(['transaction_type' => 1, 'payment_status' => 1])->whereBetween('created_at', [$startDate, $endDate])->get();
        $thisMonthTotal = $recievedRevenue->sum('transaction_ammount');

        //upcomming revenu
        $upcommingPayment = '';
        $upcommingPayment = RiderOrderPayment::join('rider_orders', 'rider_orders.order_id', '=', 'rider_order_payments.order_id')
            ->whereBetween('rider_order_payments.to_date', [$startDate, $endDate])
            ->selectRaw('SUM(rider_orders.mapped_product_price * rider_orders.subscription_days) as totalAmount')
            ->value('totalAmount');

        // // CO Emission Savings=(CO2 emissions from traditional vehicle − CO2 emissions from electric vehicle)×Distance traveled by EV  ex. tradistion
        $evDistance = Product::sum('ev_running_distance');
        $co2Saving =  number_format((0.100 / $evDistance),2);
        //$co2Saving = number_format($co2Saving, 2);
        return view($this->viewPath . '/dashboard', compact('permission', 'evstatus2W', 'evCount2W', 'evstatus3W', 'evCount3W', 'ridersArray', 'totalRevenue', 'thisMonthTotal', 'co2Saving', 'evDistance', 'upcommingPayment'));
    }
}
