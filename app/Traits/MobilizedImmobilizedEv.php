<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\RiderOrderPayment;
use Illuminate\Http\Response;

trait MobilizedImmobilizedEv
{
    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Mobi Immobi EVs
    --------------------------------------------------*/
    public function mobilizedImmobilizedEv($productId, $terminalId, $riderId, $actionType)
    {
        try {
            $apiEndpoint = 'http://139.84.165.121:8000/api/order/cmd-relay/';
            $apiToken = env('GPS_TOCKEN');

            $terminalID = $terminalId ? $terminalId : '';
            $actionTypes = $actionType == 'm' ? 0 : 1;
            $data = ['terminalID' => $terminalID, 'type' => (int)$actionTypes];
            $jsonData = json_encode($data);
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
            if (isset($jsonResponse['data']['meta'])) {
                $meta = $jsonResponse['data']['meta'];
                $message = $meta['message'];
                $success = $meta['success'];
            } else {
                $message = 500;
                $success = false;
            }
            if($message == 200 && $success == true){
                if(!empty($actionType) && $actionType == "m" && !empty($productId)){
                    $ev_status_updated = Product::where('product_id', $productId)->update(['ev_status'=>1]);
                    $ev_status_updated = Product::where('product_id', $productId)->update(['status_id' => 4]);
                    $ev_status_updated = Product::where('product_id', $productId)->update(['ev_status' => 1]);
                    $evstatus_updated = RiderOrderPayment::where('rider_id', $riderId)->where('mapped_vehicle_id', $productId)->update(['status_id' => 1]);
                    return response()->json(['msg' => 'm']);
                }elseif(!empty($actionType) && $actionType == 'im' && !empty($productId)){
                    $ev_status_updated = Product::where('product_id', $productId)->update(['ev_status'=> 2]);
                    $ev_status_updated = Product::where('product_id', $productId)->update(['status_id' => 2]);
                    $evstatus_updated = RiderOrderPayment::where('rider_id', $riderId)->where('mapped_vehicle_id', $productId)->update(['status_id'=> 2]);
                    return response()->json(['msg' => "im"]);
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
