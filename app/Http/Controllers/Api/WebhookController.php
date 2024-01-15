<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController extends ApiController
{
    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : get-near-hub-center
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function paymentWebhook(Request $request)
    {
        try {
            Log::channel('payment_webhook')->debug("Payment webhook log : " . json_encode($request->all()));
            // $result = ApiModel::getNearHubCenter($request);
            // return finalResponse($result);
            return 1;
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
