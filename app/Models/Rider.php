<?php

namespace App\Models;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Rider extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = "riders";
    protected $primaryKey = 'rider_id';
    protected $fillable = [
        'slug', 'name', 'email', 'email_verified_at', 'activated_at', 'phone', 'password', 'current_address', 'permanent_address', 'state_id', 'city_id', 'vehicle_id', 'photo', 'subscription_days', 'joining_date', 'subscription_validity', 'api_token', 'status_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at',
    ];
    protected $hidden = [
        'api_token', 'password'
    ];

    public function bankDetail()
    {
        return $this->hasOne(RiderBankDetail::class, 'rider_id', 'rider_id')->whereNull('deleted_at');
    }

    public function documents()
    {
        return $this->hasMany(RiderDocument::class, 'rider_id', 'rider_id')->whereNull('deleted_at');
    }

    public function transactions()
    {
        return $this->hasMany(RiderTransactionHistory::class, 'rider_id', 'rider_id')->whereNull('deleted_at')->orderBy('rider_transaction_id', 'DESC');
    }

    public function complaints()
    {
        return $this->hasMany(Complain::class, 'rider_id', 'rider_id')->with('category')->whereNull('deleted_at')->orderBy('created_at', 'DESC');
    }

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
                'slug' => slug(),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
            ]);
            if ($rider) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.INSERT'), (object)[]);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.INSERT_ERROR'), (object)[]);
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
                'phone' => $request->input('phone'),
                'password' => $request->input('password'),
            ];

            if (Auth::guard('rider')->attempt($credentials)) {
                config(['auth.guards.rider-api.driver' => 'session']);
                $rider = Auth::guard('rider')->user();
                $token = $rider->createToken('rider')->accessToken;
                $result = ['headerToken' => $token, 'rider' => $rider];
                return successResponse(Response::HTTP_OK, Lang::get('messages.LOGIN_SUCCESS'), $result);
            }
            return errorResponse(Response::HTTP_UNAUTHORIZED, Lang::get('messages.UNAUTHORIZED'), (object)[]);
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
            return successResponse(Response::HTTP_OK, Lang::get('messages.LOGOUT_SUCCESS'), (object)[]);
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
    public function getDetails($request)
    {
        try {
            $authId = Auth::id();
            if ($authId) {
                $result = DB::table('riders')->select(['slug', 'name', 'email', 'phone'])->where('rider_id', $authId)->whereNull('deleted_at')->first();
                return successResponse(Response::HTTP_OK, Lang::get('messages.HTTP_FOUND'), $result);
            }
            return errorResponse(Response::HTTP_NOT_FOUND, Lang::get('messages.HTTP_NOT_FOUND'), (object)[]);
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
