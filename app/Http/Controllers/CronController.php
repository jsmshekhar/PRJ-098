<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Models\RiderOrderPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Traits\MobilizedImmobilizedEv;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    use MobilizedImmobilizedEv;
    //Immobilized
    public function immobilizedVehicles(Request $request){
        try{
            $datas = RiderOrderPayment::leftJoin('rider_orders','rider_orders.order_id', 'rider_order_payments.order_id')
                ->leftJoin('products', 'products.product_id', 'rider_order_payments.mapped_vehicle_id')
                ->whereDate('rider_order_payments.to_date', '<', Carbon::now()->format('Y-m-d'))
                ->where(['rider_orders.status_id'=> 1, 'products.ev_status' => 1])
                ->whereNotNull('products.gps_emei_number')
                ->select('products.product_id', 'products.gps_emei_number', 'rider_order_payments.rider_id')
                ->get();
            if(!empty($datas)){
                foreach($datas as $data){
                    $immobilized = $this->mobilizedImmobilizedEv($data->product_id, $data->gps_emei_number, $data->rider_id, 'im');
                    Log::channel('mobi_immobilized')->debug("Immobilized log : " . json_encode($data));
                }
            }
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    //Mobilized
    public function mobilizedVehicles(Request $result)
    {
        try {
            $datas = RiderOrderPayment::leftJoin('rider_orders', 'rider_orders.order_id', 'rider_order_payments.order_id')
                ->leftJoin('products', 'products.product_id', 'rider_order_payments.mapped_vehicle_id')
                ->whereDate('rider_order_payments.from_date', '<=', Carbon::now()->format('Y-m-d'))
                ->whereDate('rider_order_payments.to_date', '>', Carbon::now()->format('Y-m-d'))
                ->where(['rider_orders.status_id' => 1, 'products.ev_status' => 2])
                ->whereNotNull('products.gps_emei_number')
                ->select('products.product_id', 'products.gps_emei_number', 'rider_order_payments.rider_id')
                ->get();
            if (!empty($datas)) {
                foreach ($datas as $data) {
                    $mmobilized = $this->mobilizedImmobilizedEv($data->product_id, $data->gps_emei_number, $data->rider_id, 'm');
                    Log::channel('mobi_immobilized')->debug("Mmobilized log : " . json_encode($data));
                }
            }
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
