<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class PaymentCallbackController extends Controller
{
    public function phonepeResponse(Request $request)
    {
        // $input = $request->all();
        // print_r($input);

        $transactionId = "65b7d20544008";
        $merchantId = "PGTESTPAYUAT";

        $saltKey = '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399';
        $saltIndex = 1;

        $finalXHeader = hash('sha256', '/pg/v1/status/' . $merchantId . '/' . $transactionId . $saltKey) . '###' . $saltIndex;

        $response = Curl::to('https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/status/' . $merchantId . '/' . $transactionId)
            ->withHeader('Content-Type:application/json')
            ->withHeader('accept:application/json')
            ->withHeader('X-VERIFY:' . $finalXHeader)
            ->withHeader('X-MERCHANT-ID:' . $transactionId)
            ->get();

        dd(json_decode($response));
    }
}
