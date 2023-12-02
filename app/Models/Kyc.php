<?php

namespace App\Models;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kyc extends Model
{
    use HasFactory;

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : profile-type
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function profileCategory($request)
    {
        try {

            $profileTypes = [
                [
                    "icon" => asset('public/images/mobile-icon/individual.png'),
                    "name" => "Individual",
                    "profile_type" => config('constants.PROFILE_CATEGORIES.INDIVIDUAL')
                ],
                [
                    "icon" => asset('public/images/mobile-icon/student.png'),
                    "name" => "Vendor",
                    "profile_type" => config('constants.PROFILE_CATEGORIES.VENDER')
                ],
                [
                    "icon" => asset('public/images/mobile-icon/student.png'),
                    "name" => "Student",
                    "profile_type" => config('constants.PROFILE_CATEGORIES.STUDENT')
                ],
                [
                    "icon" => asset('public/images/mobile-icon/corporate.png'),
                    "name" => "Corporate Employee",
                    "profile_type" => config('constants.PROFILE_CATEGORIES.CORPORATE')
                ],
            ];
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $profileTypes);
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
    public function vehiclePreferences($request)
    {
        try {

            $evTypes = DB::table('ev_types')->select(['slug', 'ev_type_name'])->whereNull('deleted_at')->where(['ev_category' => config('constants.EV_CATEGORIES.TWO_WHEELER')])->get();
            $evTypes1 = DB::table('ev_types')->select(['slug', 'ev_type_name'])->whereNull('deleted_at')->where(['ev_category' => config('constants.EV_CATEGORIES.THREE_WHEELER')])->get();
            $vehiclePrepf = [
                [
                    "icon" => asset('public/images/mobile-icon/two-wheeler.png'),
                    "name" => "Two Wheeler",
                    "category_id" => config('constants.EV_CATEGORIES.TWO_WHEELER'),
                    "ev_types" => $evTypes
                ],
                [
                    "icon" => asset('public/images/mobile-icon/three-wheeler.png'),
                    "name" => "Three Wheeler",
                    "category_id" => config('constants.EV_CATEGORIES.THREE_WHEELER'),
                    "ev_types" => $evTypes1
                ],
            ];
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $vehiclePrepf);
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
