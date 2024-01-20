<?php

namespace App\Http\Controllers\Api;

use App\Models\Kyc;
use App\Models\Rider;
use App\Models\ApiModel;
use App\Models\Faqs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class CommonDataController extends ApiController
{
    protected $riderModel, $keyModel;

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : Get FAQs
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function getFaqs(Request $request)
    {
        try {
            $result = ApiModel::getFaqs($request);
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
    Action    : Get Complain Category
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function complainCategory(Request $request)
    {
        try {
            $result = ApiModel::complainCategory($request);
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
    Action    : Get Complain Category
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function createComplaint(Request $request)
    {
        try {
            $requiredFields = [
                'category_slug' => "required",
            ];
            $messages = [];
            if (!$this->checkValidation($request, $requiredFields, $messages)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = ApiModel::createComplaint($request);
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
    Action    : Get Get Complaint List
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function getComplaints(Request $request)
    {
        try {
            $result = ApiModel::getComplaints($request);
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
    Action    : Create Service Request
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function serviceRequest(Request $request)
    {
        try {
            $requiredFields = [
                'name' => "required",
                'contact_number' => "required",
                // 'ev_number' => "required",
                'description' => "required",
            ];
            $messages = [];
            if (!$this->checkValidation($request, $requiredFields, $messages)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = ApiModel::serviceRequest($request);
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
    Action    : Create Return/Exchange Request
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function returnExchangeRequest(Request $request)
    {
        try {
            $requiredFields = [
                'request_for' => [
                    'required',
                    Rule::in([config('constants.EV_REQUEST_TYPE.RETURN'), config('constants.EV_REQUEST_TYPE.EXCHANGE')]),
                ],
                'vehicle_slug' => "required",
            ];
            $messages = [];
            if (!$this->checkValidation($request, $requiredFields, $messages)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = ApiModel::returnExchangeRequest($request);
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
    Action    : get-current-order
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function getCurrentOrder(Request $request)
    {
        try {
            $result = ApiModel::getCurrentOrder($request);
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
    Action    : get-transactions
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function getTransactions(Request $request)
    {
        try {
            $result = ApiModel::getTransactions($request);
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
    Action    : get-ev-details
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function getEvDetails(Request $request)
    {
        try {
            $result = ApiModel::getEvDetails($request);
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
    Action    : get-near-hub-center
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function getNearHubCenter(Request $request)
    {
        try {
            $result = ApiModel::getNearHubCenter($request);
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
    Action    : get-upcomming-rent
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function getUpcommingRent(Request $request)
    {
        try {
            $result = ApiModel::getUpcommingRent($request);
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
    Action    : pay-rent-bill
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function payUpcommingRent(Request $request)
    {
        try {
            $result = ApiModel::payUpcommingRent($request);
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
    Action    : get-notification
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function getNotification(Request $request)
    {
        try {
            $result = ApiModel::getNotification($request);
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
