<?php

if (!function_exists('successResponse')) {
    function successResponse($statusCode, $message = "", $result = [], $otherData = [])
    {
        $response = [
            "status" => $statusCode,
            "message" => $message,
            "result" => $result,
        ];
        if ($otherData) {
            $response['other_data'] = $otherData;
        }
        return $response;
    }
}

if (!function_exists('errorResponse')) {
    function errorResponse($statusCode, $message, $result = [])
    {
        return [
            "status" => $statusCode,
            "message" => $message,
            "result" => $result,
        ];
    }
}

if (!function_exists('errorResponseNull')) {
    function errorResponseNull($statusCode, $message, $result = [])
    {
        return [
            "status" => $statusCode,
            "message" => $message,
            "result" => $result ? $result : null,
        ];
    }
}

if (!function_exists('catchResponse')) {
    function catchResponse($statusCode, $message = "", $result = [])
    {
        return [
            "status" => $statusCode,
            "message" => $message,
            "result" => $result,
        ];
    }
}

if (!function_exists('validationResponse')) {
    function validationResponse($statusCode, $message, $result)
    {
        return response()->json(["message" => $message, "result" => $result], $statusCode);
    }
}

if (!function_exists('finalResponse')) {
    function finalResponse($result)
    {
        return response()->json($result, $result['status']);
    }
}

if (!function_exists('slug')) {
    function slug($digit = 12)
    {
        $slug = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $digit);
        return $slug;
    }
}

if (!function_exists('mroNumber')) {
    function mroNumber($digit = 5)
    {
        $mroNumber = substr(str_shuffle('0123456789'), 0, $digit);
        return $mroNumber;
    }
}

if (!function_exists('getCurlData')) {
    function getCurlData($method, $url, $data = null, $headers = [])
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, 'My New Shopify App v.1');
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            return 'Error:' . curl_error($curl);
        }
        curl_close($curl);
        return $response;
    }
}

#--------------------------------------------------------------------------#
# TODO     : To Check Json
# DEV's    : Chandra Shekhar
# DATE     : 01-04-2023
#--------------------------------------------------------------------------#
if (!function_exists('isJSON')) {
    function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}

if (!function_exists('randomPassword')) {
    function randomPassword()
    {
        $randomCharLen = 2;

        $lowerCase = "abcdefghijklmnopqrstuvwxyz";
        $upperCase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numbers = "1234567890";
        $symbols = "!@#$%&*";

        $lowerCase = str_shuffle($lowerCase);
        $upperCase = str_shuffle($upperCase);
        $numbers = str_shuffle($numbers);
        $symbols = str_shuffle($symbols);

        $randomPassword = substr($lowerCase, 0, $randomCharLen);
        $randomPassword .= substr($upperCase, 0, $randomCharLen);
        $randomPassword .= substr($numbers, 0, $randomCharLen);
        $randomPassword .= substr($symbols, 0, $randomCharLen);

        return str_shuffle($randomPassword);
    }
}
