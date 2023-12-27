<?php

namespace App\Http\Controllers\Api;

use App\Models\Kyc;
use App\Models\Rider;
use App\Models\ApiModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
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

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : uploadFile
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function uploadFile(Request $request)
    {
        try {
            $requiredFields = [
                'media_type' => [
                    'required',
                    Rule::in([1, 2]),
                ],
                'file_name' => 'required',
                'path' => 'required',
            ];
            if ($request->media_type == 1) {
                $requiredFields['file_name'] = 'required|mimes:jpeg,png,jpg,gif|max:2048';
            }
            if ($request->media_type == 2) {
                $requiredFields['file_name'] = 'required|mimes:doc,docx,xls,xlsx,pdf|max:2048';
            }

            $result = [];
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = ApiModel::uploadFile($request);
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
    Action    : updateKyc
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function updateKyc(Request $request)
    {
        try {
            $requiredFields = [
                'media_type' => [
                    'required',
                    Rule::in([1, 2]),
                ],
                'file_name' => 'required',
                'path' => 'required',
            ];
            if ($request->media_type == 1) {
                $requiredFields['file_name'] = 'required|mimes:jpeg,png,jpg,gif|max:2048';
            }
            if ($request->media_type == 2) {
                $requiredFields['file_name'] = 'required|mimes:doc,docx,xls,xlsx,pdf|max:2048';
            }

            $result = [];
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = ApiModel::uploadFile($request);
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
