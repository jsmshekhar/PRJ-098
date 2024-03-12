<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class PhonePay extends Model
{
    use HasFactory;

    public static function refundAmmount($originalTransactionId, $ammount, $lastTransactionId)
    {
        try {
            $status = false;
            $merchantId = "PGTESTPAYUAT";
            $saltKey = "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399";
            $saltIndex = 1;
            $merchantUserId = 'MUID123';
            $phonePeDomain = "https://api-preprod.phonepe.com/apis/pg-sandbox/";

            Log::channel('phonepe')->debug(__LINE__ . "\n\n\n Call start Original Transaction Id : " . $originalTransactionId);
            $paybleAmmount = $ammount * 100;
            $merchantTransactionId = uniqid();
            $payload = [
                'merchantId' => $merchantId,
                'merchantUserId' => $merchantUserId,
                'merchantTransactionId' => $merchantTransactionId,
                'originalTransactionId' => $originalTransactionId, // Pay merchantTransactionId
                'amount' => $paybleAmmount,
                'callbackUrl' => route('response'),
            ];
            $encode = base64_encode(json_encode($payload));

            Log::channel('phonepe')->debug(__LINE__ . " Refund payload : " . json_encode($payload));
            Log::channel('phonepe')->debug(__LINE__ . " Refund base64Encode : " . $encode);

            $string = $encode . '/pg/v1/refund' . $saltKey;
            $sha256 = hash('sha256', $string);

            $finalXHeader = $sha256 . '###' . $saltIndex;
            $refundResponsePayload = Curl::to($phonePeDomain . 'pg/v1/refund')
                ->withHeader('Content-Type:application/json')
                ->withHeader('X-VERIFY:' . $finalXHeader)
                ->withData(json_encode(['request' => $encode]))
                ->post();

            Log::channel('phonepe')->debug(__LINE__ . " Refund API Response : " . $refundResponsePayload);
            $refundResponse = json_decode($refundResponsePayload);
            $refundStatus = $refundResponse->success ?? false;
            $transactionId = $refundResponse->data->transactionId ?? "";

            Log::channel('phonepe')->debug(__LINE__ . " Refund API status : " . $refundStatus);
            Log::channel('phonepe')->debug(__LINE__ . " Refund API transaction Id : " . $transactionId);
            if ($refundStatus) {
                // Check phonePe status API
                $finalXHeader1 = hash('sha256', '/pg/v1/status/' . $merchantId . '/' . $merchantTransactionId . $saltKey) . '###' . $saltIndex;
                $responseResult = Curl::to($phonePeDomain . 'pg/v1/status/' . $merchantId . '/' . $merchantTransactionId)
                    ->withHeader('Content-Type:application/json')
                    ->withHeader('accept:application/json')
                    ->withHeader('X-VERIFY:' . $finalXHeader1)
                    ->withHeader('X-MERCHANT-ID:' . $merchantTransactionId)
                    ->get();

                Log::channel('phonepe')->debug(__LINE__ . " Status API Response : " . $responseResult);
                $responseDecode = json_decode($responseResult);
                $responseStatus = $responseDecode->success ?? false;
                $paymentStatus = $responseDecode->code ?? "";

                Log::channel('phonepe')->debug(__LINE__ . " Status API Response Status : " . $responseStatus);
                Log::channel('phonepe')->debug(__LINE__ . " Status API Payment Status : " . $paymentStatus);

                $paymentStatusValue = null;
                if ($paymentStatus == "PAYMENT_SUCCESS") {
                    $paymentStatusValue = 1;
                } elseif ($paymentStatus == "BAD_REQUEST") {
                    $paymentStatusValue = 5;
                } elseif ($paymentStatus == "AUTHORIZATION_FAILED") {
                    $paymentStatusValue = 6;
                } elseif ($paymentStatus == "INTERNAL_SERVER_ERROR") {
                    $paymentStatusValue = 7;
                } elseif ($paymentStatus == "TRANSACTION_NOT_FOUND") {
                    $paymentStatusValue = 8;
                } elseif ($paymentStatus == "PAYMENT_ERROR") {
                    $paymentStatusValue = 9;
                } elseif ($paymentStatus == "PAYMENT_PENDING") {
                    $paymentStatusValue = 2;
                } elseif ($paymentStatus == "PAYMENT_DECLINED") {
                    $paymentStatusValue = 4;
                } elseif ($paymentStatus == "TIMED_OUT") {
                    $paymentStatusValue = 3;
                }

                if ($responseStatus) {
                    $transactionDetails = DB::table('rider_transaction_histories')
                        ->where('rider_transaction_id', $lastTransactionId)
                        ->first();
                    $orderTransaction = [
                        "rider_id" => $transactionDetails->rider_id,
                        "order_id" => $transactionDetails->order_id,
                        "slug" => slug(),
                        "order_slug" => $transactionDetails->order_slug,
                        "transaction_ammount" => $ammount,
                        "transaction_type" => 2, //Credited to Customer account
                        'transaction_mode' => $transactionDetails->transaction_mode,

                        'status_id' => $paymentStatusValue,
                        'payment_status' => $paymentStatusValue,
                        'merchant_transaction_id' => $merchantTransactionId,
                        'transaction_id' => $transactionId,
                        'transaction_payload' => $refundResponsePayload,
                        'transaction_notes' => 'Status update while refund process is done',
                        "created_by" => $transactionDetails->rider_id,
                        "created_at" => NOW(),
                    ];
                    Log::channel('phonepe')->debug(__LINE__ . " Data needs to insert into transaction table : " . json_encode($orderTransaction));
                    $lastInsertId = DB::table('rider_transaction_histories')->insertGetId($orderTransaction);
                    if ($lastInsertId > 0) {
                        $status = true;
                        Log::channel('phonepe')->debug(__LINE__ . " Transaction table last insert ID : " . json_encode($lastInsertId));
                    }
                }
            }
            Log::channel('phonepe')->debug(__LINE__ . "Call end Original Transaction Id : " . $originalTransactionId);
            return $status;
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
