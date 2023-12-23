<?php

namespace App\Http\Controllers\Api;

use App\Models\Kyc;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

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
            $requiredFields = [
                'profile_category' => [
                    'required',
                    Rule::in([config('constants.PROFILE_CATEGORIES.INDIVIDUAL'), config('constants.PROFILE_CATEGORIES.VENDER'),  config('constants.PROFILE_CATEGORIES.STUDENT'), config('constants.PROFILE_CATEGORIES.CORPORATE')]),
                ],
            ];
            $messages = [
                'phone.profile_category' => 'The profile type is required.',
            ];
            if (!$this->checkValidation($request, $requiredFields, $messages)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = $this->keyModel->vehiclePreferences($request);
                return finalResponse($result);
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

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : vehicle-details/{slug}
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function vehicleDetails(Request $request, $slug)
    {
        try {
            $result = $this->keyModel->vehicleDetails($request, $slug);
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
    public function createOrder(Request $request)
    {
        try {
            $requiredFields = [
                'profile_category' => [
                    'required',
                    Rule::in([config('constants.PROFILE_CATEGORIES.INDIVIDUAL'), config('constants.PROFILE_CATEGORIES.VENDER'),  config('constants.PROFILE_CATEGORIES.STUDENT'), config('constants.PROFILE_CATEGORIES.CORPORATE')]),
                ],
                'rent_cycle' => [
                    'required',
                    Rule::in([config('constants.RENT_CYCLE.15_DAYS'), config('constants.RENT_CYCLE.30_DAYS')]),
                ],
                'vehicle_slug' => "required",
            ];
            $messages = [];
            if (!$this->checkValidation($request, $requiredFields, $messages)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = $this->keyModel->createOrder($request);
                return finalResponse($result);
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
