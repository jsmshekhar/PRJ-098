<?php

namespace App\Models;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhonePay extends Model
{
    use HasFactory;

    public static function refundAmmount()
    {
        try {
            $merchantId = "PGTESTPAYUAT";
            $saltKey = "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399";
            $saltIndex = 1;
            $merchantUserId = 'User123';
            $tra_id = $transactionId = 'T2401292157486451153330';
            /*echo $merchantTransactionId = uniqid();

            $data = array(
                'merchantId' => $merchantId,
                'merchantTransactionId' => $merchantTransactionId,
                'merchantUserId' => $merchantUserId,
                'amount' => 10000,
                'redirectUrl' => route('response'),
                'redirectMode' => 'POST',
                'callbackUrl' => route('response'),
                'mobileNumber' => '9999999999',
                'paymentInstrument' =>
                array(
                    'type' => 'PAY_PAGE',
                ),
            );

            $encode = base64_encode(json_encode($data));

            $string = $encode . '/pg/v1/pay' . $saltKey;
            $sha256 = hash('sha256', $string);

            $finalXHeader = $sha256 . '###' . $saltIndex;

            $response = Curl::to('https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/pay')
                ->withHeader('Content-Type:application/json')
                ->withHeader('X-VERIFY:' . $finalXHeader)
                ->withData(json_encode(['request' => $encode]))
                ->post();

            $rData = json_decode($response);

            dd($rData);
            */



            // PhonePayPaymentResponse(success=true, code=PAYMENT_SUCCESS, message=Your payment is successful., data=Data(merchantId=PGTESTPAYUAT, merchantTransactionId=8NMS2UG1JCLX, transactionId=T2401242358145511114233, amount=1350000, state=COMPLETED, responseCode=SUCCESS, paymentInstrument=PaymentInstrument(type=CARD, utr=null)))

            $payload = [
                'merchantId' => $merchantId,
                'merchantUserId' => $merchantUserId,
                'merchantTransactionId' => ($tra_id),
                'originalTransactionId' => strrev($tra_id),
                'amount' => 5000,
                'callbackUrl' => route('response'),
            ];

            $encode = base64_encode(json_encode($payload));

            $string = $encode . '/pg/v1/refund' . $saltKey;
            $sha256 = hash('sha256', $string);

            $finalXHeader = $sha256 . '###' . $saltIndex;

            $response = Curl::to('https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/refund')
                ->withHeader('Content-Type:application/json')
                ->withHeader('X-VERIFY:' . $finalXHeader)
                ->withData(json_encode(['request' => $encode]))
                ->post();

            $rData = json_decode($response);

            // dd($rData);

            $finalXHeader1 = hash('sha256', '/pg/v1/status/' . $merchantId . '/' . $tra_id . $saltKey) . '###' . $saltIndex;

            $responsestatus = Curl::to('https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/status/' . $merchantId . '/' . $tra_id)
                ->withHeader('Content-Type:application/json')
                ->withHeader('accept:application/json')
                ->withHeader('X-VERIFY:' . $finalXHeader1)
                ->withHeader('X-MERCHANT-ID:' . $tra_id)
                ->get();

            dd(json_decode($response), json_decode($responsestatus));
            // dd($rData);

            die;
            $payload = [
                "merchantId" => $merchantId,
                "merchantUserId" => $merchantUserId,
                "merchantTransactionId" => $transactionId,
                "originalTransactionId" => strrev($transactionId),
                "amount" => 100,
                "callbackUrl" => route('payment-webhook'),
            ];
            $encodedPayload = base64_encode(json_encode($payload));
            // echo "Encoded Payload: " . $encodedPayload . "\n";

            $string = $encodedPayload . '/pg/v1/refund' . $saltKey;
            // echo "String: " . $string . "\n";

            $sha256 = hash('sha256', $string);
            // echo "SHA256: " . $sha256 . "\n";

            $finalXHeader = $sha256 . "###" . $saltIndex;
            // echo "Final X-VERIFY Header: " . $finalXHeader . "\n";

            $response = Curl::to('https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/refund')
                ->withHeader('Content-Type:application/json')
                ->withHeader('X-VERIFY:' . $finalXHeader)
                ->withData(json_encode(['request' => $encodedPayload]))
                ->post();

            $result = json_decode($response);
            dd($result);
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
