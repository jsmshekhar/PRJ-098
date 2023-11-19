<?php

namespace App\Http\Controllers\Api;

use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class AuthApiController extends ApiController
{
    protected $riderModel;

    public function __construct()
    {
        $this->riderModel = new Rider();
    }

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : register
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function register(Request $request)
    {

        try {
            $requiredFields = [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'phone' => 'required',
            ];
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = $this->riderModel->register($request);
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
    Action    : login
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function login(Request $request)
    {
        try {
            $requiredFields = [
                'email' => 'required',
                'password' => 'required',
            ];
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = $this->riderModel->login($request);
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
    Developer : Raj KUmar
    Action    : logout
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function logout(Request $request)
    { 
        try {
            $result = $this->riderModel->logout($request);
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
    Action    : login
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function getRiderDetails(Request $request)
    {
        try {
            echo "get details";
            die;
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
