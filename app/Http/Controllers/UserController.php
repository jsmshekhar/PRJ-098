<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AdminAppController;

class UserController extends AdminAppController
{
    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : Site Configuration
    --------------------------------------------------*/
    public function updateProfileDetail(Request $request)
    {
        try {
            $requiredFields = [
                'user_fname' => 'required',
            ];
            $messages = [
                'user_fname.required' => 'The first name is required.',
            ];
            if (!$this->checkValidation($request, $requiredFields, $messages)) {
                $msg = $this->errorMessage;
                $msg = $msg['errorDetail'];
                $msg = json_encode($msg[0]);
                $msg = json_decode($msg, true);
                $errorText = json_encode($msg['errorMessage'][0]);
                $status = [
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'url' => "",
                    'message' => $errorText,
                ];
                return response()->json($status);
            } else {
                $user = new User();
                $userDet = $user->updateUserDetail($request);
                $userDet = json_encode($userDet);
                $userDet = json_decode($userDet, true);
                $status = [
                    'status' => $userDet['original']['status'],
                    'message' => $userDet['original']['message'],
                ];
                return response()->json($status);
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
    Developer : Raj Kumar
    Action    : Change Password
    --------------------------------------------------*/
    public function changePassword(Request $request)
    {
        try {
            $requiredFields = [
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ];
            $messages = [
                'password.required' => 'The first name is required.',
            ];
            if (!$this->checkValidation($request, $requiredFields, $messages)) {
                $msg = $this->errorMessage;
                $msg = $msg['errorDetail'];
                $msg = json_encode($msg[0]);
                $msg = json_decode($msg, true);
                $errorText = json_encode($msg['errorMessage'][0]);
                $status = [
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'url' => "",
                    'message' => $errorText,
                ];
                return response()->json($status);
            } else {
                $user = new User();
                $userDet = $user->changePassword($request);
                $userDet = json_encode($userDet);
                $userDet = json_decode($userDet, true);
                $status = [
                    'status' => $userDet['original']['status'],
                    'message' => $userDet['original']['message'],
                ];
                return response()->json($status);
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
