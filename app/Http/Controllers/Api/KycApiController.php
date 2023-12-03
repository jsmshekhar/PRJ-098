<?php

namespace App\Http\Controllers\Api;

use App\Models\Kyc;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class KycApiController extends ApiController
{
    protected $riderModel, $keyModel;

    public function __construct()
    {
        $this->riderModel = new Rider();
        $this->keyModel = new Kyc();
    }

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : profile-category
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function profileCategory(Request $request)
    {
        try {
            $result = $this->keyModel->profileCategory($request);
            return finalResponse($result);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : vehicle-preferences
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function vehiclePreferences(Request $request)
    {
        try {
            $result = $this->keyModel->vehiclePreferences($request);
            return finalResponse($result);
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
