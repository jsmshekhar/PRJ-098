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
        $input = $request->all();

        $saltKey = '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399';
        $saltIndex = 1;

        $finalXHeader = hash('sha256', '/pg/v1/status/' . $input['merchantId'] . '/' . $input['transactionId'] . $saltKey) . '###' . $saltIndex;

        $response = Curl::to('https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/' . $input['merchantId'] . '/' . $input['transactionId'])
            ->withHeader('Content-Type:application/json')
            ->withHeader('accept:application/json')
            ->withHeader('X-VERIFY:' . $finalXHeader)
            ->withHeader('X-MERCHANT-ID:' . $input['transactionId'])
            ->get();

        dd(json_decode($response));
    }
}
