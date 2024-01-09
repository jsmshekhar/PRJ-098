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
}
