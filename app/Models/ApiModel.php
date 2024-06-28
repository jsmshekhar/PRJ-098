<?php

namespace App\Models;

use App\Models\ComplainCategory;
use App\Models\Faqs;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class ApiModel extends Model
{
    use HasFactory;

    public function clean($string)
    {
        $string = str_replace(' ', '-', $string);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }

    public static function uploadFile($request)
    {
        try {
            if ($request->hasFile('file_name')) {
                $filePath = $request->path ?? "";
                $file = $request->file('file_name');

                $uploadedPath = 'upload/' . $filePath;

                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $fileName = strtolower($fileName) . time() . '.' . $file->extension();

                $uploadStatus = $request->file_name->move(public_path($uploadedPath), $fileName);
                if ($uploadStatus) {
                    $returnPath = asset('public/' . $uploadedPath . '/' . $fileName);
                    return successResponse(Response::HTTP_OK, Lang::get('messages.UPLOAD_SUCCESS'), [
                        'fileName' => $fileName,
                        'filePath' => $returnPath,
                    ]);
                }
            }
            return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('messages.UPLOAD_ERROR'));
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
    Action    : get faqs
    --------------------------------------------------*/
    public static function getFaqs($request)
    {
        try {
            $result = Faqs::select(['slug', 'title', 'description', 'created_at'])->whereNull('deleted_at')->get();
            if (!$result->isEmpty()) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $result);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), []);
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
    Action    : get complain category
    --------------------------------------------------*/
    public static function complainCategory($request)
    {
        try {
            $result = ComplainCategory::select(['slug', 'category_name'])->whereNull('deleted_at')->orderBy('category_name', 'ASC')->get();
            if (!$result->isEmpty()) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $result);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), []);
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
    Action    : Get complaint list
    --------------------------------------------------*/
    public static function getComplaints($request)
    {
        try {
            $riderId = Auth::id();
            $results = DB::table('complains AS c')
                ->join('complain_categories AS cc', 'cc.slug', '=', 'c.complain_category')
                ->select(
                    'c.slug',
                    'c.description',
                    'cc.category_name',
                    DB::raw('CASE c.status_id WHEN 1 THEN "Resolved" WHEN 3 THEN "Discard" ELSE "Pending" END as status'),
                    'c.created_at'
                )
                ->where('c.rider_id', '=', $riderId)
                ->get();
            if (!$results->isEmpty()) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $results);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), []);
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
    Action    : Create Complaint
    --------------------------------------------------*/
    public static function createComplaint($request)
    {
        try {
            $riderId = Auth::id();
            $categorySlug = $request->category_slug ?? "";
            $category = ComplainCategory::where('slug', $categorySlug)->whereNull('deleted_at')->first();

            $complainNumber = 101;
            $complain = Complain::whereNull('deleted_at')->orderBy('complain_id', 'DESC')->first();
            if (!is_null($complain)) {
                $complainNumber = (int) $complain->complain_number;
                $complainNumber = $complainNumber + 1;
            }

            if (!is_null($category)) {
                $complaint = [
                    'slug' => slug(),
                    'complain_category' => $categorySlug,
                    'description' => $request->description ?? "",
                    'complain_number' => $complainNumber,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone,
                    'status_id' => 2,
                    'rider_id' => $riderId,
                    'role_id' => $category->role_id,
                ];
                $status = Complain::insert($complaint);
                if ($status) {
                    return successResponse(Response::HTTP_OK, Lang::get('messages.INSERT'), (object) []);
                }
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), []);
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
    Action    : Create Complaint
    --------------------------------------------------*/
    public static function serviceRequest($request)
    {
        try {
            $riderId = Auth::id();
            $serviceNumber = 101;
            $complain = EvServiceRequset::whereNull('deleted_at')->orderBy('requset_id', 'DESC')->first();
            if (!is_null($complain)) {
                $serviceNumber = (int) $complain->service_number;
                $serviceNumber = $serviceNumber + 1;
            }

            if (!is_null($riderId)) {
                $serviceRequest = [
                    'slug' => slug(),
                    'service_number' => $serviceNumber,
                    'rider_id' => $riderId,
                    'name' => $request->name ?? "",
                    'number' => $request->contact_number ?? "",
                    'ev_number' => $request->ev_number,
                    'description' => $request->description ?? "",
                ];
                $status = EvServiceRequset::insert($serviceRequest);
                if ($status) {
                    return successResponse(Response::HTTP_OK, Lang::get('messages.INSERT'), (object) []);
                }
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), []);
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
    Action    : Create Complaint
    --------------------------------------------------*/
    public static function returnExchangeRequest($request)
    {
        try {
            $riderId = Auth::id();
            $vehicleSlug = $request->vehicle_slug ?? null;
            $requestFor = (int) $request->request_for ?? null;
            $vehicle = DB::table('products')->where('slug', $vehicleSlug)->whereNull('deleted_at')->first();
            if (!is_null($vehicle)) {
                $vehicleId = $vehicle->product_id;
                $orderDetails = DB::table('rider_orders')->where(['rider_id' => $riderId, 'vehicle_id' => $vehicleId, 'status_id' => 1, 'payment_status' => 1])->whereNull('deleted_at')->first();

                if (!is_null($orderDetails)) {
                    $orderId = $orderDetails->order_id;
                    $hubId = $orderDetails->hub_id;
                    $mappedVehicleId = $orderDetails->mapped_vehicle_id;
                    $assignedDate = $orderDetails->assigned_date;

                    $isRequested = ReturnExchange::where(['order_id' => $orderId, 'request_for' => $requestFor])->whereNull('deleted_at')->first();
                    if (is_null($isRequested)) {
                        $requestData = [
                            'slug' => slug(),
                            'order_id' => $orderId,
                            'hub_id' => $hubId,
                            'rider_id' => $riderId,
                            'mapped_vehicle_id' => $mappedVehicleId,
                            'assigned_date' => $assignedDate,
                            'request_for' => $requestFor,
                        ];
                        $status = ReturnExchange::insert($requestData);
                        if ($status) {
                            return successResponse(Response::HTTP_OK, Lang::get('messages.INSERT'), (object) []);
                        }
                    }
                }
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), []);
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
    public static function getCurrentOrder($request)
    {
        try {
            $riderId = Auth::id();
            $currentOrder = ApiModel::getCurrentOrderDetails();
            if (!empty($currentOrder)) {
                $orderCode = $currentOrder['order_code'];
                $vehicleSlug = $currentOrder['vehicle_slug'];
                $orderSlug = $orderCode . "#" . slug();
                $result = ['order_code' => $orderSlug, 'vehicle_slug' => $vehicleSlug];
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $currentOrder);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object) []);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    public static function getCurrentOrderDetails($riderId = "")
    {
        try {
            $riderId = empty($riderId) ? Auth::id() : $riderId;
            $currentOrder = DB::table('rider_orders as ro')
                ->join('products as p', 'p.product_id', '=', 'ro.vehicle_id')
                ->select('ro.slug as order_code', 'p.slug as vehicle_slug')
                ->where('ro.rider_id', '=', $riderId)
                ->where('ro.status_id', '=', config('constants.ORDER_STATUS.ASSIGNED'))
                ->whereIn('ro.payment_status', [config('constants.PAYMENT_STATUS.SUCCESS'), config('constants.PAYMENT_STATUS.COD')])
                ->orderBy('ro.order_id', 'DESC')
            // ->whereDate('ro.subscription_validity', '>=', NOW())
                ->first();
            $result = [];
            if (!is_null($currentOrder)) {
                $result = ['order_code' => $currentOrder->order_code, 'vehicle_slug' => $currentOrder->vehicle_slug];
            }
            return $result;
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
    Message   : For Mobile App 1-Debit, 2-Credit
    --------------------------------------------------*/
    public static function getTransactions($request)
    {
        try {
            $riderId = Auth::id();
            $records = DB::table('rider_transaction_histories as th')
                ->where('th.rider_id', $riderId)
                ->whereNull('th.deleted_at')
                ->select(
                    'th.slug',
                    'th.transaction_ammount',
                    'th.created_at',
                    DB::raw('CASE th.transaction_type WHEN 1 THEN "Debit" WHEN 2 THEN "Credit" ELSE "" END as transaction_type'),
                    DB::raw('CASE th.transaction_mode WHEN 1 THEN "Card" WHEN 2 THEN "Wallet" WHEN 3 THEN "UPI" WHEN 4 THEN "COD" END as transaction_mode'),
                )->get();
            if (!is_null($records)) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $records);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object) []);
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
    public static function getEvDetails($request)
    {
        try {
            $currentOrder = ApiModel::getCurrentOrderDetails();
            if (!empty($currentOrder)) {
                $slug = $currentOrder['vehicle_slug'] ?? null;
                $riderId = Auth::id();
                $basePath = asset('public/upload/');
                $details = DB::table('products as p')
                    ->join('ev_types as et', 'p.ev_type_id', '=', 'et.ev_type_id')
                    ->join('hubs', 'p.hub_id', '=', 'hubs.hub_id')
                    ->where('p.slug', $slug)
                    ->whereNull('p.deleted_at')
                    ->select(
                        'p.slug',
                        'p.product_id',
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
                        'et.ev_type_name',
                        'p.chassis_number',
                        'p.ev_number',
                        'hubs.hubId AS hub_id',
                    )->first();

                if ($details) {
                    $vehicleId = $details->product_id;
                    $orderDetails = DB::table('rider_orders')->select([
                        'order_id',
                        'slug',
                        'subscription_days AS rent_cycle',
                    ])->where(['rider_id' => $riderId, 'vehicle_id' => $vehicleId])->first();

                    $orderId = $orderDetails->order_id;

                    $lastTransaction = RiderTransactionHistory::where(['rider_id' => $riderId, 'order_id' => $orderId, 'transaction_type' => 1, 'payment_status' => 1])->orderBy('rider_transaction_id', 'DESC')->first();

                    $payments = RiderOrderPayment::selectRaw('*')->where(['rider_id' => $riderId, 'order_id' => $orderId])->where('to_date', '>=', NOW())->orderBy('rider_order_payment_id', 'DESC')->first();
                    $subcriptionStatus = !is_null(($payments)) ? 'Active' : 'Inactive';

                    $orderDetails->last_payment_date = !is_null($lastTransaction) ? dateTimeFormat($lastTransaction->created_at) : NOW();
                    $orderDetails->subcription_status = $subcriptionStatus;

                    $orderDetails->total_runing = 200;
                    return successResponse(Response::HTTP_OK, Lang::get('messages.HTTP_FOUND'), $details, $orderDetails);
                }
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object) []);
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
    public static function getNearHubCenter($request)
    {
        try {
            $radius = 6371; // Earth radius in kilometers
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $records = Hub::select(
                'slug',
                'hubId',
                'city',
                'state',
                'zip_code',
                'full_address',
                'latitude',
                'longitude',
                DB::raw('(
                    ' . $radius . ' * acos( cos( radians(' . $latitude . ') )
                    * cos( radians( latitude ) )
                    * cos( radians( longitude ) - radians(' . $longitude . ') )
                    + sin( radians(' . $latitude . ') )
                    * sin( radians( latitude ) )
                ) ) AS distance')
            )->orderBy('distance')->get();

            if (!is_null($records)) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $records);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object) []);
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
    public static function getUpcommingRent($request)
    {
        try {
            $currentOrder = ApiModel::getCurrentOrderDetails();
            $riderId = Auth::id();
            if (!empty($currentOrder)) {
                $vehicleSlug = $currentOrder['vehicle_slug'] ?? null;
                $orderCode = $currentOrder['order_code'] ?? null;
                $orderDetail = RiderOrder::where('slug', $orderCode)->whereNull('deleted_at')->first();
                if (!is_null($orderDetail)) {
                    $orderId = $orderDetail->order_id;

                    $payments = RiderOrderPayment::selectRaw('*')->where(['rider_id' => $riderId, 'order_id' => $orderId])->orderBy('rider_order_payment_id', 'DESC')->first();

                    $lastDate = !is_null(($payments)) ? dateTimeFormat($payments->to_date) : '';

                    $rentCycle = $orderDetail->subscription_days;
                    $orderSlug = $orderCode . "#" . slug();
                    $records = [
                        'order_code' => $orderSlug,
                        'basic_rent' => $orderDetail->product_price,
                        'payble_rent' => (string) ($rentCycle * $orderDetail->product_price),
                        'last_date' => $lastDate,
                        'rent_cycle' => $rentCycle,
                        'extra_distance_charge' => 0,
                        'penalty_of_extra_days' => 0,
                    ];
                    return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $records);
                }
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object) []);
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
    public static function payUpcommingRent($request)
    {
        try {
            $riderId = Auth::id();
            $orderSlug = $orderCode = $request->order_code;
            $orderCodeArr = explode("#", $orderCode);
            $orderCode = $orderCodeArr[0] ?? "";
            $order = RiderOrder::where('rider_id', $riderId)->where('slug', $orderCode)->whereNull('deleted_at')->first();
            $status = false;
            if ($riderId && !is_null($order)) {
                $paymentStatus = (int) $request->payment_status;
                $transactionMode = (int) $request->transaction_mode;
                $orderId = (int) $order->order_id;

                $orderTransaction = [
                    "rider_id" => $riderId,
                    "order_id" => $orderId,
                    "slug" => slug(),
                    "order_slug" => $orderSlug,
                    "transaction_ammount" => $request->gross_ammount,
                    "transaction_type" => 1, //Credited
                    'transaction_mode' => $transactionMode,

                    'status_id' => $paymentStatus,
                    'payment_status' => $paymentStatus,
                    'merchant_transaction_id' => $request->merchant_transaction_id ?? null,
                    'transaction_id' => $request->transaction_id ?? null,
                    'transaction_payload' => $request->transaction_payload ?? null,
                    'transaction_notes' => 'Pay upcommig bill from app',
                    "created_by" => $riderId,
                    "created_at" => NOW(),
                ];
                $status = DB::table('rider_transaction_histories')->insertGetId($orderTransaction);
                if ($status) {
                    $mappedVehicleId = $order->mapped_vehicle_id;
                    $rentCycle = (int) $order->subscription_days;

                    $payments = RiderOrderPayment::selectRaw('*')->where(['rider_id' => $riderId, 'order_id' => $orderId])->orderBy('rider_order_payment_id', 'DESC')->first();

                    $fromDate = !is_null($payments) ? Carbon::parse($payments->to_date)->addDay() : '';
                    $toDate = !is_null($payments) ? Carbon::parse($payments->to_date)->addDay($rentCycle) : '';

                    $riderOrderPayments = [
                        'slug' => slug(),
                        'order_id' => $orderId,
                        'rider_id' => $riderId,
                        'mapped_vehicle_id' => $mappedVehicleId,
                        'from_date' => $fromDate,
                        'to_date' => $toDate,
                        'status_id' => 1,
                    ];
                    RiderOrderPayment::insert($riderOrderPayments);
                    $result = ['order_code' => $orderCode];
                    return successResponse(Response::HTTP_OK, Lang::get('messages.INSERT'), $result);
                }
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object) []);
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
    public static function getNotification($request)
    {
        try {
            $riderId = Auth::id();
            $notifications = RiderNotification::select(['slug', 'title', 'description'])->where('rider_id', $riderId)->get();
            if (!empty($notifications)) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $notifications);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object) []);
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
    Action    : get-kyc-documents
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public static function getKycDocuments($request)
    {
        try {
            $riderId = Auth::id();
            $profileType = Auth::user()->profile_type;

            $documentType = [1, 2, 3, 9, 5, 4, 8];
            $documents = DB::table('rider_documents')->select(['slug', 'name', 'status_id', 'document_type'])->whereNull('document_type')->where('rider_id', $riderId)->get()->toArray();
            $docsList = [];
            $docs = RiderDocument::select(['slug', 'name', 'status_id', 'document_type'])->whereIn('document_type', $documentType)->where('rider_id', $riderId)->get();
            if (!empty($docs)) {
                foreach ($docs as $d) {
                    $docsList[$d->document_type] = [
                        'slug' => $d->slug,
                        'name' => $d->name,
                        'status_id' => $d->status_id,
                        'document_type' => $d->document_type,
                    ];
                }
            }

            switch ($profileType) {
                case 1: // Corporate
                    $documentsStats = [
                        [
                            'slug' => !empty($docsList) && isset($docsList[1]) ? $docsList[1]['name'] : "",
                            'name' => 'Aadhar Card',
                            'status_id' => !empty($docsList) && isset($docsList[1]) ? 1 : 2,
                            'document_type' => 1,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[3]) ? $docsList[3]['name'] : "",
                            'name' => 'Driving license',
                            'status_id' => !empty($docsList) && isset($docsList[3]) ? 1 : 2,
                            'document_type' => 3,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[9]) ? $docsList[9]['name'] : "",
                            'name' => 'Employee Id card',
                            'status_id' => !empty($docsList) && isset($docsList[9]) ? 1 : 2,
                            'document_type' => 9,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[5]) ? $docsList[5]['name'] : "",
                            'name' => 'Pan card',
                            'status_id' => !empty($docsList) && isset($docsList[5]) ? 1 : 2,
                            'document_type' => 5,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[4]) ? $docsList[4]['name'] : "",
                            'name' => 'Rent agreement/electricity bill',
                            'status_id' => !empty($docsList) && isset($docsList[4]) ? 1 : 2,
                            'document_type' => 4,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[8]) ? $docsList[8]['name'] : "",
                            'name' => 'Terms and conditions agreement',
                            'status_id' => !empty($docsList) && isset($docsList[8]) ? 1 : 2,
                            'document_type' => 8,
                        ],
                    ];
                    break;
                case 2: //Individual
                    $documentsStats = [
                        [
                            'slug' => !empty($docsList) && isset($docsList[1]) ? $docsList[1]['name'] : "",
                            'name' => 'Aadhar Card',
                            'status_id' => !empty($docsList) && isset($docsList[1]) ? 1 : 2,
                            'document_type' => 1,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[3]) ? $docsList[3]['name'] : "",
                            'name' => 'Driving license',
                            'status_id' => !empty($docsList) && isset($docsList[3]) ? 1 : 2,
                            'document_type' => 3,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[5]) ? $docsList[5]['name'] : "",
                            'name' => 'Pan card',
                            'status_id' => !empty($docsList) && isset($docsList[5]) ? 1 : 2,
                            'document_type' => 5,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[4]) ? $docsList[4]['name'] : "",
                            'name' => 'Rent agreement/electricity bill',
                            'status_id' => !empty($docsList) && isset($docsList[4]) ? 1 : 2,
                            'document_type' => 4,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[8]) ? $docsList[8]['name'] : "",
                            'name' => 'Terms and conditions agreement',
                            'status_id' => !empty($docsList) && isset($docsList[8]) ? 1 : 2,
                            'document_type' => 8,
                        ],
                    ];
                    break;
                case 3: //Student
                    $documentsStats = [
                        [
                            'slug' => !empty($docsList) && isset($docsList[1]) ? $docsList[1]['name'] : "",
                            'name' => 'Aadhar Card',
                            'status_id' => !empty($docsList) && isset($docsList[1]) ? 1 : 2,
                            'document_type' => 1,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[3]) ? $docsList[3]['name'] : "",
                            'name' => 'Driving license',
                            'status_id' => !empty($docsList) && isset($docsList[3]) ? 1 : 2,
                            'document_type' => 3,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[5]) ? $docsList[5]['name'] : "",
                            'name' => 'Pan card',
                            'status_id' => !empty($docsList) && isset($docsList[5]) ? 1 : 2,
                            'document_type' => 5,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[4]) ? $docsList[4]['name'] : "",
                            'name' => 'Rent agreement/electricity bill',
                            'status_id' => !empty($docsList) && isset($docsList[4]) ? 1 : 2,
                            'document_type' => 4,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[9]) ? $docsList[9]['name'] : "",
                            'name' => 'Student Id card',
                            'status_id' => !empty($docsList) && isset($docsList[9]) ? 1 : 2,
                            'document_type' => 9,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[8]) ? $docsList[8]['name'] : "",
                            'name' => 'Terms and conditions agreement',
                            'status_id' => !empty($docsList) && isset($docsList[8]) ? 1 : 2,
                            'document_type' => 8,
                        ],
                    ];
                    break;
                case 4: //Vender
                    $documentsStats = [
                        [
                            'slug' => !empty($docsList) && isset($docsList[1]) ? $docsList[1]['name'] : "",
                            'name' => 'Aadhar Card',
                            'status_id' => !empty($docsList) && isset($docsList[1]) ? 1 : 2,
                            'document_type' => 1,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[2]) ? $docsList[2]['name'] : "",
                            'name' => 'Credit Score',
                            'status_id' => !empty($docsList) && isset($docsList[2]) ? 1 : 2,
                            'document_type' => 2,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[3]) ? $docsList[3]['name'] : "",
                            'name' => 'Driving license',
                            'status_id' => !empty($docsList) && isset($docsList[3]) ? 1 : 2,
                            'document_type' => 3,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[5]) ? $docsList[5]['name'] : "",
                            'name' => 'Pan card',
                            'status_id' => !empty($docsList) && isset($docsList[5]) ? 1 : 2,
                            'document_type' => 5,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[4]) ? $docsList[4]['name'] : "",
                            'name' => 'Rent agreement/electricity bill',
                            'status_id' => !empty($docsList) && isset($docsList[4]) ? 1 : 2,
                            'document_type' => 4,
                        ],
                        [
                            'slug' => !empty($docsList) && isset($docsList[8]) ? $docsList[8]['name'] : "",
                            'name' => 'Terms and conditions agreement',
                            'status_id' => !empty($docsList) && isset($docsList[8]) ? 1 : 2,
                            'document_type' => 8,
                        ],
                    ];
                    break;
            }

            $finalDocumentList = array_merge($documentsStats, $documents);
            if (!empty($finalDocumentList)) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $finalDocumentList);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object) []);
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
    Action    : upload-current-order
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public static function uploadKycDocuments($request)
    {
        try {
            $riderId = Auth::id();
            $record = [
                'rider_id' => $riderId,
                'slug' => slug(),
                'name' => !empty($request->name) ? $request->name : null,
                'front_pic' => !empty($request->front_image) ? $request->front_image : null,
                'back_pic' => !empty($request->back_image) ? $request->back_image : null,
                'document_type' => $request->document_type ?? 0,
            ];
            $status = RiderDocument::insert($record);
            if (!empty($status)) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.INSERT'), (object) []);
            }
            return errorResponse(Response::HTTP_OK, Lang::get('messages.INSERT_ERROR'), (object) []);
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
    Action    : get-your-orders
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public static function getYourOrders($request)
    {
        try {
            $riderId = Auth::id();
            $result = [];
            $orders = DB::table('rider_orders')
                ->join('products', 'products.product_id', '=', 'rider_orders.vehicle_id')
                ->select(
                    DB::raw('rider_orders.slug AS order_code'),
                    DB::raw('rider_orders.accessories_items AS accessories_items'),
                    DB::raw('products.slug AS vehicle_slug')
                )
                ->where('rider_id', $riderId)
                ->orderBy('rider_orders.order_id', 'DESC')
                ->get();
            if (!empty($orders)) {
                foreach ($orders as $order) {
                    $orderCode = $order->order_code ?? null;
                    $vehicleSlug = $order->vehicle_slug ?? null;
                    $basePath = asset('public/upload/');

                    $vehicleDetail = DB::table('products as p')
                        ->join('ev_types as et', 'p.ev_type_id', '=', 'et.ev_type_id')
                        ->where('p.slug', $vehicleSlug)
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

                    $jsonData = $order->accessories_items;
                    $accessoriesItems = json_decode($jsonData, true);
                    $accessoriesResult = [];
                    foreach ($accessoriesItems as $key => $accessoriesIts) {
                        $slugItem = $accessoriesIts['slug'];
                        $accessories = DB::table('accessories as acc')
                            ->whereNull('acc.deleted_at')
                            ->where('slug', $slugItem)
                            ->select(
                                'acc.slug',
                                'acc.title',
                                'acc.price',
                                'acc.image',
                                DB::raw("CONCAT('$basePath','/accessories/', acc.image) AS image_path"),
                            )->first();
                        $accessoriesResult[$key] = $accessoriesIts;
                        $accessoriesResult[$key]['image_path'] = $accessories->image_path ?? "";
                    }

                    $result[] = [
                        'slug' => $orderCode,
                        'vehicle_detail' => $vehicleDetail,
                        'accessories_items' => $accessoriesResult,
                    ];
                }
            }

            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $result);

            return errorResponse(Response::HTTP_OK, Lang::get('messages.HTTP_NOT_FOUND'), (object) []);
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
