<?php

namespace App\Models;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
    Return    : Json - API
    --------------------------------------------------*/
    public function profileCategory($request)
    {
        try {

            $profileTypes = [
                [
                    "icon" => asset('public/images/mobile-icon/individual.png'),
                    "name" => "Individual",
                    "profile_category" => config('constants.PROFILE_CATEGORIES.INDIVIDUAL')
                ],
                [
                    "icon" => asset('public/images/mobile-icon/student.png'),
                    "name" => "Vendor",
                    "profile_category" => config('constants.PROFILE_CATEGORIES.VENDER')
                ],
                [
                    "icon" => asset('public/images/mobile-icon/student.png'),
                    "name" => "Student",
                    "profile_category" => config('constants.PROFILE_CATEGORIES.STUDENT')
                ],
                [
                    "icon" => asset('public/images/mobile-icon/corporate.png'),
                    "name" => "Corporate Employee",
                    "profile_category" => config('constants.PROFILE_CATEGORIES.CORPORATE')
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
            // $profileType = (int)$request->profile_category;
            $profileType = Auth::user()->profile_type;
            $basePath = asset('public/upload/product');
            $twoWheelers = DB::table('products as p')
                ->join('ev_types as et', 'p.ev_type_id', '=', 'et.ev_type_id')
                ->where('p.profile_category', $profileType)
                ->where('p.is_display_on_app', 1)
                ->whereNull('p.deleted_at')
                ->where('p.ev_category_id', config('constants.EV_CATEGORIES.TWO_WHEELER'))
                ->select(
                    'p.slug',
                    'p.ev_category_id',
                    'p.title',
                    'p.profile_category',
                    DB::raw("CONCAT('$basePath','/', p.image) AS image"),
                    'p.per_day_rent as per_day_rent',
                    DB::raw('CASE p.bettery_type WHEN 1 THEN "Swappable" WHEN 2 THEN "Fixed" ELSE "" END as battery_type'),
                    'p.km_per_charge as km_per_charge',
                    DB::raw('CASE p.bike_type WHEN 1 THEN "Cargo Bike" WHEN 2 THEN "Normal Bike" ELSE "" END as bike_type'),
                    'et.ev_type_name'
                )
                ->get();

            $threeWheelers = DB::table('products as p')
                ->join('ev_types as et', 'p.ev_type_id', '=', 'et.ev_type_id')
                ->where('p.profile_category', $profileType)
                ->where('p.is_display_on_app', 1)
                ->whereNull('p.deleted_at')
                ->where('p.ev_category_id', config('constants.EV_CATEGORIES.THREE_WHEELER'))
                ->select(
                    'p.slug',
                    'p.ev_category_id',
                    'p.title',
                    'p.profile_category',
                    DB::raw("CONCAT('$basePath','/', p.image) AS image"),
                    'p.per_day_rent as per_day_rent',
                    DB::raw('CASE p.bettery_type WHEN 1 THEN "Swappable" WHEN 2 THEN "Fixed" ELSE "" END as battery_type'),
                    'p.km_per_charge as km_per_charge',
                    DB::raw('CASE p.bike_type WHEN 1 THEN "Cargo Bike" WHEN 2 THEN "Normal Bike" ELSE "" END as bike_type'),
                    'et.ev_type_name'
                )
                ->get();

            $vehiclePrepf = [
                [
                    "icon" => asset('public/images/mobile-icon/two-wheeler.png'),
                    "name" => "Two Wheeler",
                    "category_id" => config('constants.EV_CATEGORIES.TWO_WHEELER'),
                    "ev_list" => $twoWheelers
                ],
                [
                    "icon" => asset('public/images/mobile-icon/three-wheeler.png'),
                    "name" => "Three Wheeler",
                    "category_id" => config('constants.EV_CATEGORIES.THREE_WHEELER'),
                    "ev_list" => $threeWheelers
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

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : vehicle-details
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function vehicleDetails($request, $slug)
    {
        try {
            $basePath = asset('public/upload/');
            $details = DB::table('products as p')
                ->join('ev_types as et', 'p.ev_type_id', '=', 'et.ev_type_id')
                ->where('p.slug', $slug)
                ->whereNull('p.deleted_at')
                ->select(
                    'p.slug',
                    'p.title',
                    'p.description',
                    'p.speed',
                    'p.profile_category',
                    'p.image',
                    DB::raw("CONCAT('$basePath','/product/', p.image) AS image_path"),
                    'p.per_day_rent as per_day_rent',
                    DB::raw('CASE p.bettery_type WHEN 1 THEN "Swappable" WHEN 2 THEN "Fixed" ELSE "" END as battery_type'),
                    DB::raw('CASE p.ev_category_id WHEN 1 THEN "2 Wheeler" WHEN 2 THEN "3 Wheeler" END as ev_category'),
                    'p.km_per_charge as km_per_charge',
                    DB::raw('CASE p.bike_type WHEN 1 THEN "Cargo Bike" WHEN 2 THEN "Normal Bike" ELSE "" END as bike_type'),
                    'et.ev_type_name'
                )
                ->first();

            $accessories = DB::table('accessories as acc')
                ->whereNull('acc.deleted_at')
                ->select(
                    'acc.slug',
                    'acc.title',
                    'acc.price',
                    'acc.image',
                    DB::raw("CONCAT('$basePath','/accessories/', acc.image) AS image_path"),
                )
                ->get();
            $result = ['vehicle' => $details, 'accessories' => $accessories];
            if ($details) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.HTTP_FOUND'), $result);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object)[]);
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
    Action    : vehicle-details
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function createOrder($request)
    {
        try {
            $riderId = Auth::id();
            $vehicleSlug = $request->vehicle_slug ?? null;
            $vehicle = DB::table('products')->where('slug', $vehicleSlug)->whereNull('deleted_at')->first();
            if (!is_null($vehicle)) {
                if (isset($request->accessories) && !empty($request->accessories)) {
                    $accessories = $request->accessories;
                    $accessoriesSlug = $accessoriesIds = [];
                    foreach ($accessories as $accessory) {
                        $accessoriesSlug[] = $accessory['slug'] ?? null;
                    }
                    if (!empty($accessoriesSlug)) {
                        $accessoriesIds = DB::table('accessories')->whereIn('slug', $accessoriesSlug)->whereNull('deleted_at')->pluck('accessories_id')->toArray();
                    }
                }
                $orderCode = slug();
                $orderDetails = [
                    "rider_id" => $riderId,
                    "slug" => $orderCode,

                    "vehicle_id" => $vehicle->product_id,
                    "product_price" => $vehicle->per_day_rent,
                    "product_name" => $vehicle->title,

                    "accessories_id" => implode(',', $accessoriesIds),
                    "accessories_items" => json_encode($accessories),

                    "subscription_days" => $request->rent_cycle,
                    "order_date" => NOW(),
                    "ordered_ammount" => $request->gross_ammount ?? null,
                    "security_ammount" => $request->security_ammount ?? null,
                    "status_id" => 2,
                    "requested_payload" => json_encode($request->all()),
                    "created_by" => $riderId,
                    "created_at" => NOW(),
                ];
                $orderId = DB::table('rider_orders')->insertGetId($orderDetails);
                if ($orderId) {
                    $result = ['order_code' => $orderCode];
                    $orderTransaction = [
                        "rider_id" => $riderId,
                        "order_id" => $orderId,
                        "slug" => slug(),
                        "status_id" => 2,
                        "rider_id" => $riderId,
                        "created_by" => $riderId,
                        "created_at" => NOW(),
                    ];
                    DB::table('rider_transaction_histories')->insertGetId($orderTransaction);
                    return successResponse(Response::HTTP_OK, Lang::get('messages.ORDER_CREATED'), $result);
                }
                return errorResponse(Response::HTTP_OK, Lang::get('messages.ORDER_ERROR'), (object)[]);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object)[]);
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
    Action    : update-kys-steps
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public static function updateKycSteps($request)
    {
        try {
            $profileType = Auth::user()->profile_type;
            $riderId = Auth::id();
            $requestedStep = (int)$request->step;

            $stepOne = 1;
            $stepTwo = 2;
            $stepThree = 3;
            $stepFour = 4;
            $rider = Rider::where('rider_id', $riderId)->whereNull('deleted_at')->first();
            $status = false;
            if ($riderId && !is_null($rider)) {

                if ($requestedStep == $stepOne && is_null($rider->is_step_selfie_done)) {
                    $record = [
                        'photo' => $request->profile_image ?? null,
                    ];
                    $status = Rider::where('rider_id', $riderId)->update($record);
                    if ($status) {
                        Rider::where('rider_id', $riderId)->update(['is_step_selfie_done' => NOW()]);
                    }
                } elseif ($requestedStep == $stepTwo && is_null($rider->is_personal_detail_done)) {
                    $record = [
                        'name' => $request->full_name ?? $rider->name,
                        'email' => $request->email ?? null,
                        'alternate_phone' => $request->alternate_number ?? null,
                        'parent_phone' => $request->parent_phone ?? null,
                        'sibling_phone' => $request->sibling_phone ?? null,
                        'owner_phone' => $request->owner_phone ?? null,
                        'current_address' => $request->current_address ?? null,
                        'permanent_address' => $request->permanent_address ?? null,
                        'company_name' => $request->company_name ?? null,
                        'company_address' => $request->company_address ?? null,
                        'state_name' => $request->state ?? null,
                    ];
                    $status = Rider::where('rider_id', $riderId)->update($record);
                    if ($status) {
                        Rider::where('rider_id', $riderId)->update(['is_personal_detail_done' => NOW()]);
                    }
                } elseif ($requestedStep == $stepThree && is_null($rider->is_id_proof_done)) {
                    // 1 => Aadhar Card, 2 => Credit Score, 3 => Driving License, 4 => Electicity Bill, 5 => Pan Card, 6 => Passpost, 7 => Voter Id
                    $record = [
                        [
                            'rider_id' => $riderId,
                            'slug' => slug(),
                            'name' => 'Aadhar Card',
                            'front_pic' => !empty($request->aadhar_card) && $request->aadhar_card['front_image'] ? $request->aadhar_card['front_image'] : null,
                            'back_pic' => !empty($request->aadhar_card) && $request->aadhar_card['back_image'] ? $request->aadhar_card['back_image'] : null,
                            'document_type' => 1,
                        ],
                        [
                            'rider_id' => $riderId,
                            'slug' => slug(),
                            'name' => 'Credit Score',
                            'front_pic' => !empty($request->credit_score) && $request->credit_score['front_image'] ? $request->credit_score['front_image'] : null,
                            'back_pic' => !empty($request->credit_score) && $request->credit_score['back_image'] ? $request->credit_score['back_image'] : null,
                            'document_type' => 2,
                        ],
                        [
                            'rider_id' => $riderId,
                            'slug' => slug(),
                            'name' => 'Driving License',
                            'front_pic' => !empty($request->driving_licence) && $request->driving_licence['front_image'] ? $request->driving_licence['front_image'] : null,
                            'back_pic' => !empty($request->driving_licence) && $request->driving_licence['back_image'] ? $request->driving_licence['back_image'] : null,
                            'document_type' => 3,
                        ],
                        [
                            'rider_id' => $riderId,
                            'slug' => slug(),
                            'name' => 'Electicity Bill',
                            'front_pic' => !empty($request->electicity_bill) && $request->electicity_bill['front_image'] ? $request->electicity_bill['front_image'] : null,
                            'back_pic' => !empty($request->electicity_bill) && $request->electicity_bill['back_image'] ? $request->electicity_bill['back_image'] : null,
                            'document_type' => 4,
                        ],
                        [
                            'rider_id' => $riderId,
                            'slug' => slug(),
                            'name' => 'Pan Card',
                            'front_pic' => !empty($request->pan_card) && $request->pan_card['front_image'] ? $request->pan_card['front_image'] : null,
                            'back_pic' => !empty($request->pan_card) && $request->pan_card['back_image'] ? $request->pan_card['back_image'] : null,
                            'document_type' => 5,
                        ],

                    ];
                    DB::table('rider_documents')->where('rider_id', $riderId)->delete();
                    $status = RiderDocument::insert($record);
                    if ($status) {
                        Rider::where('rider_id', $riderId)->update(['is_id_proof_done' => NOW()]);
                    }
                } elseif ($requestedStep == $stepFour && is_null($rider->is_bank_detail_done)) {
                    $record = [
                        'rider_id' => $riderId,
                        'slug' => slug(),
                        'account_type' => $request->account_type,
                        'account_name' => $request->account_name,
                        'bank_name' => $request->bank_name ?? null,
                        'account_no' => $request->account_no,
                        'ifsc_code' => $request->ifsc_code,
                        'branch_name' => $request->branch_name ?? null,
                        'upi_id' => $request->upi_id,
                    ];
                    DB::table('rider_bank_details')->where('rider_id', $riderId)->delete();
                    $status = RiderBankDetail::insert($record);
                    if ($status) {
                        Rider::where('rider_id', $riderId)->update(['is_bank_detail_done' => NOW()]);
                    }
                }

                if ($status) {
                    $riderDetails = Rider::where('rider_id', $riderId)->whereNull('deleted_at')->first();
                    $result = [
                        "is_step_selfie_done" => !is_null($riderDetails->is_step_selfie_done) ? true : false,
                        "is_personal_detail_done" => !is_null($riderDetails->is_personal_detail_done) ? true : false,
                        "is_id_proof_done" => !is_null($riderDetails->is_id_proof_done) ? true : false,
                        "is_bank_detail_done" => !is_null($riderDetails->is_bank_detail_done) ? true : false,
                    ];
                    return successResponse(Response::HTTP_OK, Lang::get('messages.UPDATE'), $result);
                }
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object)[]);
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
    Action    : get-kys-step
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public static function getKycStep($request)
    {
        try {
            $riderId = Auth::id();
            $riderDetails = Rider::where('rider_id', $riderId)->whereNull('deleted_at')->first();
            if ($riderId && !is_null($riderDetails)) {
                $result = [
                    "is_step_selfie_done" => !is_null($riderDetails->is_step_selfie_done) ? true : false,
                    "is_personal_detail_done" => !is_null($riderDetails->is_personal_detail_done) ? true : false,
                    "is_id_proof_done" => !is_null($riderDetails->is_id_proof_done) ? true : false,
                    "is_bank_detail_done" => !is_null($riderDetails->is_bank_detail_done) ? true : false,
                ];
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $result);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object)[]);
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
