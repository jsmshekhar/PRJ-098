<?php

namespace App\Models;

use App\Models\Faqs;
use Illuminate\Http\Response;
use App\Models\ComplainCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
                $complainNumber = (int)$complain->complain_number;
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
                    return successResponse(Response::HTTP_OK, Lang::get('messages.INSERT'), (object)[]);
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
                $serviceNumber = (int)$complain->service_number;
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
                    return successResponse(Response::HTTP_OK, Lang::get('messages.INSERT'), (object)[]);
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
            $requestFor = (int)$request->request_for ?? null;
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
                            return successResponse(Response::HTTP_OK, Lang::get('messages.INSERT'), (object)[]);
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
            $currentOrder = DB::table('rider_orders as ro')
                ->join('products as p', 'p.product_id', '=', 'ro.vehicle_id')
                ->select('ro.slug as order_code', 'p.slug as vehicle_slug')
                ->where('ro.rider_id', '=', $riderId)
                ->where('ro.status_id', '=', config('constants.ORDER_STATUS.ASSIGNED'))
                ->where('ro.payment_status', '=', config('constants.PAYMENT_STATUS.SUCCESS'))
                ->whereDate('ro.subscription_validity', '>=', NOW())
                ->first();
            if (!is_null($currentOrder)) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $currentOrder);
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
