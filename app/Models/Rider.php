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
        'slug', 'name', 'email', 'email_verified_at', 'activated_at', 'phone', 'password', 'current_address', 'permanent_address', 'state_id', 'city_id', 'vehicle_id', 'photo', 'subscription_days', 'joining_date', 'subscription_validity', 'api_token', 'status_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at', 'customer_id',
    ];
    protected $hidden = [
        'api_token', 'password'
    ];

    protected $appends = [
        'profile_type_name','kyc_status_name'
    ];

    public function getProfileTypeNameAttribute()
    {
        if (is_null($this->profile_type) || $this->profile_type == "") {
            return "";
        } else {
            switch ($this->profile_type) {
                case 1:
                    return 'Corporate';
                    break;
                case 2:
                    return 'Individual';
                    break;
                case 3:
                    return 'Student';
                    break;
                case 4:
                    return 'Vender';
                    break;
                default:
                    return "";
            }
        }
    }

    public function getKycStatusNameAttribute()
    {
        if (is_null($this->kyc_status) || $this->kyc_status == "") {
            return "";
        } else {
            switch ($this->kyc_status) {
                case 1:
                    return 'Verified';
                    break;
                case 2:
                    return 'Pending';
                    break;
                case 3:
                    return 'Red Flag';
                    break;
                default:
                    return "";
            }
        }
    }

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
            $customerId = 101;
            $riderDetail = Rider::whereNull('deleted_at')->orderBy('rider_id', 'DESC')->first();
            if (!is_null($riderDetail)) {
                $customerId = (int)$riderDetail->customer_id;
                $customerId = $customerId + 1;
            }
            $rider = Rider::create([
                'slug' => slug(),
                'customer_id' => $customerId,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
                'profile_type' => $request->input('profile_category'),
            ]);
            if ($rider) {
                $rider = new Rider();
                $result = $rider->login($request);
                $result = !empty($result) && isset($result['result']) ? $result['result'] :  (object)[];
                return successResponse(Response::HTTP_OK, Lang::get('messages.INSERT'), $result);
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
                $riderDetails = [];
                if (!is_null($rider)) {
                    $isKyc = DB::table('riders')->where('rider_id', $rider->rider_id)->whereNotNull('is_step_selfie_done')->whereNotNull('is_personal_detail_done')->whereNotNull('is_id_proof_done')->whereNotNull('is_bank_detail_done')->first();
                    $kycStatus = !is_null($isKyc) ? 1 : 0;
                    $riderDetails = [
                        "slug" => $rider->slug,
                        "name" => $rider->name,
                        "email" => $rider->email,
                        "phone" => $rider->phone,
                        "photo" => $rider->photo,
                        "profile_category" => (int)$rider->profile_type,
                    ];
                }
                $token = $rider->createToken('rider')->accessToken;
                $result = ['headerToken' => $token, 'isKycCompleted' => $kycStatus, 'rider' => $riderDetails];
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

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : validate-user
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function validateUser($request)
    {
        try {
            $phoneNumber = $request->phone;
            $rider = DB::table('riders')->select(['slug', 'name', 'email', 'phone'])->where('phone', $phoneNumber)->whereNull('deleted_at')->first();
            if (!is_null($rider) && $rider) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.HTTP_FOUND'), $rider);
            }
            return errorResponse(Response::HTTP_NOT_FOUND, Lang::get('messages.INVALID_PHONE'), (object)[]);
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
    Action    : validate-user
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function resetPassword($request)
    {
        $phoneNumber = $request->phone;
        $rider = Rider::where('phone', $phoneNumber)->whereNull('deleted_at')->first();
        if (!is_null($rider) && $rider) {
            $status = $rider->update(['password' => Hash::make($request->password)]);
            if ($status) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.PASSWORD_UPDATE'), (object)[]);
            }
        }
        return errorResponse(Response::HTTP_NOT_FOUND, Lang::get('messages.INVALID_PHONE'), (object)[]);
    }
}
