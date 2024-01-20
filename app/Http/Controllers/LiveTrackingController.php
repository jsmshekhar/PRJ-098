<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use App\Models\Product;
use App\Models\Rider;
use App\Models\RiderOrder;
use App\Models\RiderOrderPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Traits\MobilizedImmobilizedEv;
use Illuminate\Support\Facades\Lang;

class LiveTrackingController extends AdminAppController
{
    use MobilizedImmobilizedEv;

    public $viewPath;

    public function __construct()
    {
        $this->viewPath = "admin/tracking";
    }

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Tracking
    --------------------------------------------------*/
    public function index()
    {
        $permission = User::getPermissions();
        $riders = RiderOrder::leftJoin('products','products.product_id', '=', 'rider_orders.mapped_vehicle_id')
            ->leftJoin('riders', 'riders.rider_id', '=', 'rider_orders.rider_id')
            ->whereNotNull('products.gps_emei_number')
            ->where(['rider_orders.status_id' => 1, 'riders.status_id'=> 1, 'products.status_id' => 4])
            ->select(
                'riders.name',
                'products.chassis_number'
            )->orderBy('riders.name', 'ASC')->take(20)->get();

        return view($this->viewPath . '/index', compact('permission', 'riders'));
    }

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Auto search ajax
    --------------------------------------------------*/
    public function riderAutoSerch(Request $request)
    {
        $query = $request->get('query');
        $riders = RiderOrder::leftJoin('products', 'products.product_id', '=', 'rider_orders.mapped_vehicle_id')
        ->leftJoin('riders', 'riders.rider_id', '=', 'rider_orders.rider_id')
        ->where(['rider_orders.status_id' => 1, 'riders.status_id' => 1, 'products.status_id' => 4])
        ->select(
            'riders.name',
            'products.chassis_number'
        )->where('name', 'like', '%' . $query . '%')->orderBy('riders.name', 'ASC')->take(30)->get();
        return response()->json($riders);
    }

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : GPS record
    --------------------------------------------------*/
    public function getGpsEvDetails(Request $request){
        $terminalId = Product::where('chassis_number', $request->input('chassis_number'))->pluck('gps_emei_number')->toArray();
        $riders= RiderOrder::leftJoin('products', 'products.product_id', '=', 'rider_orders.mapped_vehicle_id')
            ->leftJoin('riders', 'riders.rider_id', '=', 'rider_orders.rider_id')
            ->where('products.chassis_number', $request->input('chassis_number'))
            ->where(['rider_orders.status_id' => 1, 'riders.status_id' => 1, 'products.status_id' => 4])
            ->select(
                'riders.rider_id',
                'riders.name',
                'riders.customer_id',
                'products.chassis_number',
                'products.ev_number',
                'riders.phone',
                'products.product_id',
                'products.gps_emei_number'
            )->first();
           
        $renewwal_date = RiderOrderPayment::where('rider_id', $riders->rider_id)->orderBy('rider_order_payment_id', 'DESC')->select('from_date', 'to_date')->first();
        $riders->from_date = $renewwal_date ? dateFormat($renewwal_date->from_date) : '';
        $riders->to_date = $renewwal_date ? dateFormat($renewwal_date->to_date) : '';


        $apiEndpoint = 'http://139.84.165.121:8000/api/location/get-by-imei/';
        $apiToken = env('GPS_TOCKEN');
        
        $jsonData = json_encode(['terminalID' => $terminalId]);
        $ch = curl_init($apiEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Token ' . $apiToken,
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        $jsonResponse = json_decode($response, true);

        if (isset($jsonResponse['data'][0]['location']['lat']) && isset($jsonResponse['data'][0]['location']['lon'])) {
            $latitude = $jsonResponse['data'][0]['location']['lat'];
            $longitude = $jsonResponse['data'][0]['location']['lon'];
            $riders->lat = $latitude;
            $riders->long = $longitude;
        } else {
            $riders->lat = "28.666328";
            $riders->long = "77.43129";
        }

        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);
      
        return response()->json($riders);
    }

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : ev mobilized immobilized
    --------------------------------------------------*/
    public function evMobilizedImmobilized(Request $request){

        try{
            $productId = $request->input('productId');
            $terminalId = $request->input('terminalId');
            $actionType = $request->input('actionType');
            $riderId = $request->input('riderId');
         
            $mibilizedImmobilized = $this->mobilizedImmobilizedEv($productId, $terminalId, $riderId, $actionType);
            if($mibilizedImmobilized && $actionType == 'm'){
                $status = [
                    'status' => Response::HTTP_OK,
                    'message' => Lang::get('Mobilized Successsfully'),
                ];
            }elseif($mibilizedImmobilized && $actionType == 'im'){
                $status = [
                    'status' => Response::HTTP_OK,
                    'message' => Lang::get('Immobilized Successsfully'),
                ];
            } else {
                $status = [
                    'status' => Response::HTTP_OK,
                    'message' => Lang::get('Something went wrong!'),
                ];
            }
            return response()->json($status);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }
}
