<?php

namespace App\Models;

use Illuminate\Http\Response;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhonePay extends Model
{
    use HasFactory;

    public static function refundAmmount($originalTransactionId, $ammount)
    {
        try {
            $merchantId = "PGTESTPAYUAT";
            $saltKey = "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399";
            $saltIndex = 1;
            $merchantUserId = 'MUID123';
            $phonePeDomain = "https://api-preprod.phonepe.com/apis/pg-sandbox/";

            $paybleAmmount = $ammount * 100;
            $payload = [
                'merchantId' => $merchantId,
                'merchantUserId' => $merchantUserId,
                'merchantTransactionId' => uniqid(),
                'originalTransactionId' => $originalTransactionId, // Pay merchantTransactionId
                'amount' => $paybleAmmount,
                'callbackUrl' => route('response'),
            ];
            $encode = base64_encode(json_encode($payload));

            Log::channel('phonepe')->debug(__LINE__ . " Refund payload : " . json_encode($payload));
            Log::channel('phonepe')->debug(__LINE__ . " Refund base64Encode : " . $encode);
            Log::channel('phonepe')->debug(__LINE__ . " Original Transaction Id : " . $originalTransactionId);

            $string = $encode . '/pg/v1/refund' . $saltKey;
            $sha256 = hash('sha256', $string);

            $finalXHeader = $sha256 . '###' . $saltIndex;
            $response = Curl::to($phonePeDomain . 'pg/v1/refund')
                ->withHeader('Content-Type:application/json')
                ->withHeader('X-VERIFY:' . $finalXHeader)
                ->withData(json_encode(['request' => $encode]))
                ->post();


            // Check phonePe status API
            $finalXHeader1 = hash('sha256', '/pg/v1/status/' . $merchantId . '/' . $originalTransactionId . $saltKey) . '###' . $saltIndex;
            $responsestatus = Curl::to($phonePeDomain . 'pg/v1/status/' . $merchantId . '/' . $originalTransactionId)
                ->withHeader('Content-Type:application/json')
                ->withHeader('accept:application/json')
                ->withHeader('X-VERIFY:' . $finalXHeader1)
                ->withHeader('X-MERCHANT-ID:' . $originalTransactionId)
                ->get();

            dd(json_decode($response), json_decode($responsestatus));
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
