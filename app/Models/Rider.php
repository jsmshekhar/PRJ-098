<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Laravel\Passport\HasApiTokens;

class Rider extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = "riders";
    protected $primaryKey = 'rider_id';
    protected $fillable = [
        'name', 'email', 'password', 'phone',
    ];
    protected $hidden = [
        'api_token',
    ];

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : register
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function register($request)
    {
        try {
            $rider = Rider::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
            ]);
            if ($rider) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.INSERT'), []);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.INSERT_ERROR'), []);
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
    public function login($request)
    {
        try {
            $credentials = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ];

            if (Auth::guard('rider')->attempt($credentials)) {
                config(['auth.guards.rider-api.driver' => 'session']);
                $rider = Auth::guard('rider')->user();
                $token = $rider->createToken('rider')->accessToken;
                $result = ['headerToken' => $token, 'rider' => $rider];
                return successResponse(Response::HTTP_OK, Lang::get('messages.LOGIN_SUCCESS'), $result);
            }
            return errorResponse(Response::HTTP_UNAUTHORIZED, Lang::get('messages.UNAUTHORIZED'), []);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    public function logout($request)
    {
        try {
            $token = $request->user('rider-api')->token();
            $token->revoke();
            return successResponse(Response::HTTP_OK, Lang::get('messages.LOGOUT_SUCCESS'), []);
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
