<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;

class AdminAppController extends Controller
{
    protected $errorMessage;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : checkValidation
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function checkValidation($request, $params = [], $messages = [])
    {
        $validator = Validator::make(
            $request->all(),
            $params,
            !empty($messages) ? $messages : Lang::get('validation')
        );
        if ($validator->fails()) {
            $this->errorMessage['errorDetail'] = [];
            foreach ($validator->errors()->getMessages() as $key => $message) {
                $this->errorMessage['errorDetail'][] = [
                    'errorField' => $key,
                    'errorMessage' => str_replace("field", "", $message),
                ];
            }
            return 0;
        } else {
            return 1;
        }
    }
}
