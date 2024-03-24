<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataExport extends Model
{
    public static function customerExport($request)
    {
        try {
            $finalResult = [];
            $records = Rider::whereNull('deleted_at');
            if (isset($request->customer_id) && !empty($request->customer_id)) {
                $records = $records->where('slug', $request->customer_id);
            }
            if (isset($request->name) && !empty($request->name)) {
                $records = $records->where('name', 'like', '%' . $request->name . '%');
            }
            if (isset($request->email) && !empty($request->email)) {
                $records = $records->where('email', 'like', '%' . $request->email . '%');
            }
            if (isset($request->phone) && !empty($request->phone)) {
                $records = $records->where('phone', $request->phone);
            }
            if (isset($request->joining_date) && !empty($request->joining_date)) {
                $records = $records->whereDate('created_at', $request->joining_date);
            }
            $records = $records->orderBy('created_at', 'DESC')->get();
            if ($records->isNotEmpty()) {

                foreach ($records as $index => $record) {
                    $status = "Pending";
                    if ($record->status_id == 1) {
                        $status = "Verified";
                    }
                    $finalResult[] = [
                        "key_id" => $index + 1,
                        "customer_id" => $record->slug ?? "",
                        "name" => $record->name ?? "",
                        "email" => $record->email ?? "",
                        "phone" => $record->phone ?? "",
                        "joining_date" => $record->created_at ? dateFormat($record->created_at) : "",
                        "subscription_validity" => $record->created_at ? dateFormat($record->created_at) : "",
                        "verification_status" => $status,
                    ];
                }
            }
            return $finalResult;
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    public static function hubInventoryExport($request)
    {
        try {
            $finalResult = [];
            $hubSlug = $request->hub_slug;
            $hub = Hub::where('slug', $hubSlug)->whereNull('deleted_at')->first();
            if (!empty($hub)) {

                $vehicles = Product::leftJoin('rider_orders', function ($join) {
                    $join->on('rider_orders.mapped_vehicle_id', '=', 'products.product_id');
                    $join->where('rider_orders.status_id', 1);
                })
                    ->leftJoin('riders', function ($join) {
                        $join->on('riders.rider_id', '=', 'rider_orders.rider_id');
                        $join->where('riders.status_id', 1);
                    })
                    ->leftJoin('ev_types as et', 'products.ev_type_id', '=', 'et.ev_type_id')
                    ->where('products.hub_id', $hub->hub_id)
                    ->whereNull('products.deleted_at')
                    ->select(
                        'products.*',
                        'et.ev_type_name',
                        'et.slug as ev_type_slug',
                        'riders.customer_id',
                        'rider_orders.payment_status',
                        'rider_orders.status_id as statusid',
                        'rider_orders.assigned_date',
                        'rider_orders.cluster_manager',
                        'rider_orders.tl_name',
                        'rider_orders.client_name',
                        'rider_orders.client_address',
                        'rider_orders.slug as order_slug',
                        'riders.kyc_status',
                        DB::raw('CASE
                        WHEN riders.kyc_status = 1 THEN "Verified"
                        WHEN riders.kyc_status = 2 THEN "Pending"
                        WHEN riders.kyc_status = 3 THEN "Red Flag"
                        ELSE ""
                    END as kycStatus'),
                        DB::raw('CASE
                        WHEN products.ev_category_id = 1 THEN "Two Wheeler"
                        WHEN products.ev_category_id = 2 THEN "Three Wheeler"
                        ELSE ""
                    END as ev_category_name'),
                        DB::raw('CASE
                        WHEN products.profile_category = 1 THEN "Corporate"
                        WHEN products.profile_category = 2 THEN "Individual"
                        WHEN products.profile_category = 3 THEN "Student"
                        WHEN products.profile_category = 4 THEN "Vendor"
                        ELSE ""
                    END as profile_category_name')
                    );

                if (isset($request->ev) && !empty($request->ev)) {
                    $vehicles = $vehicles->where('products.ev_number', 'LIKE', "%{$request->ev}%");
                }
                if (isset($request->ch_no) && !empty($request->ch_no)) {
                    $vehicles = $vehicles->where('products.chassis_number', 'LIKE', "%{$request->ch_no}%");
                }
                if (isset($request->ev_cat) && !empty($request->ev_cat)) {
                    $vehicles = $vehicles->where('products.ev_category_id', 'LIKE', $request->ev_cat);
                }
                if (isset($request->pro_cat) && !empty($request->pro_cat)) {
                    $vehicles = $vehicles->where('riders.profile_type', 'LIKE', $request->pro_cat);
                }
                if (isset($request->status) && !empty($request->status)) {
                    $vehicles = $vehicles->where('products.status_id', 'LIKE', $request->status);
                }
                if (isset($request->pay_status) && !empty($request->pay_status)) {
                    $vehicles = $vehicles->where('rider_orders.payment_status', 'LIKE', $request->pay_status);
                }
                if (isset($request->kyc) && !empty($request->kyc)) {
                    $vehicles = $vehicles->where('riders.kyc_status', 'LIKE', $request->kyc);
                }
                if (isset($request->gps) && !empty($request->gps) && $request->gps == 1) {
                    $vehicles = $vehicles->where('products.gps_emei_number', '!=', '');
                }
                if (isset($request->gps) && !empty($request->gps) && $request->gps == 2) {
                    $vehicles = $vehicles->where('products.gps_emei_number', '');
                }

                $vehicles = $vehicles->orderBy('products.created_at', 'DESC')->get();

                if ($vehicles->isNotEmpty()) {

                    foreach ($vehicles as $index => $record) {
                        $status = "";
                        if ($record->status_id == 1) {
                            $status = "Active";
                        } elseif ($record->status_id == 2) {
                            $status = "Inactive";
                        } elseif ($record->status_id == 3) {
                            $status = "NF";
                        } elseif ($record->status_id == 4) {
                            $status = "Assigned";
                        } elseif ($record->status_id == 6) {
                            $status = "RFD";
                        }
                        $paymentStatus = "";
                        if ($record->payment_status == 1) {
                            $paymentStatus = "Paid";
                        } elseif ($record->payment_status == 2) {
                            $paymentStatus = "Pending";
                        } elseif ($record->payment_status == 3) {
                            $paymentStatus = "Failed";
                        } elseif ($record->payment_status == 4) {
                            $paymentStatus = "Rejected";
                        }
                        $kycStatus = "";
                        if ($record->kyc_status == 1) {
                            $kycStatus = "Verified";
                        } elseif ($record->kyc_status == 2) {
                            $kycStatus = "Pending";
                        } elseif ($record->kyc_status == 3) {
                            $kycStatus = "Red Flag";
                        }

                        $finalResult[] = [
                            "key_id" => $index + 1,
                            "ev_number" => $record->ev_number ?? "",
                            "chassis_number" => $record->chassis_number ?? "",
                            "gps_device" => $record->gps_emei_number ? 'Installed' : 'No Device',
                            "ev_category_name" => $record->ev_category_name,
                            "customer_id" => $record->customer_id ? "CUS" . $record->customer_id : "",
                            "profile" => $record->profile_category_name ?? "",
                            "status" => $status,
                            "paymenent_status" => $paymentStatus,
                            "kyc_status" => $kycStatus,
                        ];
                    }
                }
                return $finalResult;
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

    public static function hubEmployeeExport($request)
    {
        try {
            $finalResult = [];
            $hubSlug = $request->hub_slug;
            $hub = Hub::where('slug', $hubSlug)->whereNull('deleted_at')->first();
            if (!empty($hub)) {

                $employees = User::select('users.*', 'roles.name as role_name')
                    ->where('users.hub_id', $hub->hub_id)
                    ->where('users.role_id', '!=', 0)
                    ->whereNull('users.deleted_at')
                    ->orderBy('users.created_at', 'DESC')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.role_id')
                    ->get();

                if ($employees->isNotEmpty()) {

                    foreach ($employees as $index => $record) {
                        $status = "Inactive";
                        if ($record->status_id == 1) {
                            $status = "Active";
                        }
                        $finalResult[] = [
                            "key_id" => $index + 1,
                            "id" => $record->emp_id ? 'EVA2Z' . $record->emp_id : "",
                            "name" => $record->first_name . " " . $record->last_name,
                            "email" => $record->email ?? "",
                            "phone" => $record->phone ?? "",
                            "role" => ucfirst($record->role_name),
                            "status" => $status,
                        ];
                    }
                }
                return $finalResult;
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

    public static function hubAccessoriesExport($request)
    {
        try {
            $finalResult = [];
            $hubSlug = $request->hub_slug;
            $hub = Hub::where('slug', $hubSlug)->whereNull('deleted_at')->first();
            if (!empty($hub)) {

                $hubParts = HubPartAccessories::leftJoin('accessories', 'accessories.accessories_id', '=', 'hub_part_accessories.accessories_id')
                    ->leftJoin('hubs', 'hubs.hub_id', '=', 'hub_part_accessories.hub_id')
                    ->leftJoin('users', 'users.user_id', '=', 'hub_part_accessories.created_by')
                    ->where('hub_part_accessories.hub_id', $hub->hub_id)
                    ->whereNull('hub_part_accessories.deleted_at');

                $hubParts = $hubParts->select(
                    'hub_part_accessories.*',
                    'hubs.hubId',
                    'hubs.city',
                    'accessories.price',
                    DB::raw('CONCAT(users.first_name, " ", users.last_name) AS name'),
                    DB::raw('CASE
                    WHEN accessories.accessories_category_id = 1 THEN "Helmet"
                    WHEN accessories.accessories_category_id = 2 THEN "T-Shirt"
                    WHEN accessories.accessories_category_id = 3 THEN "Mobile Holder"
                END as accessories')
                );
                $hubParts = $hubParts->orderBy('hub_part_accessories.created_at', 'DESC')->get();

                if ($hubParts->isNotEmpty()) {

                    foreach ($hubParts as $index => $record) {
                        $status = "Inactive";
                        if ($record->status_id == 1) {
                            $status = "Raised";
                        } elseif ($record->status_id == 2) {
                            $status = "Shipped";
                        } elseif ($record->status_id == 3) {
                            $status = "Completed";
                        } elseif ($record->status_id == 4) {
                            $status = "Rejected";
                        }

                        $finalResult[] = [
                            "key_id" => $index + 1,
                            "accessories" => $record->accessories ?? "",
                            "requested_qty" => $record->requested_qty ?? "",
                            "assigned_qty" => $record->assigned_qty ?? "",
                            "requested_cost" => '₹' . $record->accessories_price * $record->requested_qty,
                            "assigned_cost" => '₹' . $record->accessories_price * $record->assigned_qty,
                            "requested_date" => dateFormat($record->requested_date),
                            "assigned_date" => dateFormat($record->assign_date),
                            "status" => $status,
                        ];
                    }
                }
                return $finalResult;
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

    public static function usersExport($request)
    {
        try {
            $finalResult = [];

            $users = User::select('users.*', 'roles.name as role_name')
                ->where(function ($query) {
                    $query->where('users.hub_id', Auth::user()->hub_id)
                        ->where('users.role_id', '!=', 0)
                        ->where('users.user_id', '!=', Auth::user()->user_id);
                })
                ->orWhere(function ($query) {
                    $query->where('users.created_by', Auth::user()->user_id)
                        ->where('users.role_id', '!=', 0)
                        ->where('users.user_id', '!=', Auth::user()->user_id);
                })
                ->whereNull('users.deleted_at')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.role_id');

            if (isset($request->emp_id) && !empty($request->emp_id)) {
                $users = $users->where('users.emp_id', $request->emp_id);
            }
            if (isset($request->first_name) && !empty($request->first_name)) {
                $users = $users->where('users.first_name', 'LIKE', "%{$request->first_name}%");
            }
            if (isset($request->last_name) && !empty($request->last_name)) {
                $users = $users->where('users.last_name', 'LIKE', "%{$request->last_name}%");
            }
            if (isset($request->email) && !empty($request->email)) {
                $users = $users->where('users.email', 'LIKE', "%{$request->email}%");
            }
            if (isset($request->phone) && !empty($request->phone)) {
                $users = $users->where('users.phone', 'LIKE', "%{$request->phone}%");
            }
            if (isset($request->role) && !empty($request->role)) {
                $users = $users->where('users.role_id', $request->role);
            }

            $users = $users->orderBy('users.created_at', 'DESC')->get();
            if ($users->isNotEmpty()) {

                foreach ($users as $index => $record) {
                    $status = "Inactive";
                    if ($record->status_id == 1) {
                        $status = "Active";
                    }
                    $finalResult[] = [
                        "key_id" => $index + 1,
                        "id" => $record->emp_id ? 'EVA2Z' . $record->emp_id : "",
                        "name" => $record->first_name . " " . $record->last_name,
                        "email" => $record->email ?? "",
                        "phone" => $record->phone ?? "",
                        "role" => ucfirst($record->role_name),
                        "status" => $status,
                    ];
                }
            }
            return $finalResult;

        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    public static function vehicleExport($request)
    {
        try {
            $finalResult = [];

            $vehicles = RiderOrder::leftJoin('products', 'products.product_id', '=', 'rider_orders.mapped_vehicle_id')
                ->leftJoin('riders', 'riders.rider_id', '=', 'rider_orders.rider_id')
                ->join('hubs', 'hubs.hub_id', '=', 'products.hub_id')
                ->where(['rider_orders.status_id' => 1, 'riders.status_id' => 1, 'products.status_id' => 4])
                ->select(
                    'rider_orders.payment_status',
                    'products.ev_number',
                    'riders.name',
                    'riders.phone',
                    'riders.customer_id',
                    'riders.slug',
                    'products.ev_status',
                    DB::raw('CASE
                        WHEN products.ev_category_id = 1 THEN "Two Wheeler"
                        WHEN products.ev_category_id = 2 THEN "Three Wheeler"
                        ELSE ""
                    END as ev_category_name'),

                    DB::raw('CASE
                        WHEN riders.profile_type = 1 THEN "Corporate"
                        WHEN riders.profile_type = 2 THEN "Individual"
                        WHEN riders.profile_type = 3 THEN "Student"
                        WHEN riders.profile_type = 4 THEN "Vendor"
                        ELSE ""
                    END as profile_category_name'),
                    'hubs.hubid'
                );
            if (isset($request->is_search) && $request->is_search == 1) {
                if (isset($request->ev_no) && !empty($request->ev_no)) {
                    $vehicles = $vehicles->where('products.ev_number', 'LIKE', "%{$request->ev_no}%");
                }
                if (isset($request->ev_cat) && !empty($request->ev_cat)) {
                    $vehicles = $vehicles->where('products.ev_category_id', 'LIKE', "%{$request->ev_cat}%");
                }
                if (isset($request->cus_id) && !empty($request->cus_id)) {
                    $vehicles = $vehicles->where('riders.customer_id', 'LIKE', "%{$request->cus_id}%");
                }
                if (isset($request->ph) && !empty($request->ph)) {
                    $vehicles = $vehicles->where('riders.phone', 'LIKE', "%{$request->ph}%");
                }
                if (isset($request->hid) && !empty($request->hid)) {
                    $vehicles = $vehicles->where('hubs.hub_id', 'LIKE', "%{$request->hid}%");
                }
                if (isset($request->pay) && !empty($request->pay)) {
                    $vehicles = $vehicles->where('rider_orders.payment_status', 'LIKE', "%{$request->pay}%");
                }
            }
            $vehicles = $vehicles->orderBy('rider_orders.created_at', 'DESC')->get();
            if ($vehicles->isNotEmpty()) {

                foreach ($vehicles as $index => $record) {
                    $vehicleStatus = "Immobilized";
                    if ($record->ev_status == 1) {
                        $vehicleStatus = "Mobilized";
                    }

                    $paymentStatus = "";
                    if ($record->payment_status == 1) {
                        $paymentStatus = "Paid";
                    } elseif ($record->payment_status == 2) {
                        $paymentStatus = "Pending";
                    } elseif ($record->payment_status == 3) {
                        $paymentStatus = "Failed";
                    } elseif ($record->payment_status == 4) {
                        $paymentStatus = "Reject";
                    }

                    $finalResult[] = [
                        "key_id" => $index + 1,
                        "ev_number" => $record->ev_number ?? "",
                        "profile_type" => $record->profile_category_name ?? "",
                        "ev_category" => $record->ev_category_name ?? "",
                        "customer_id" => $record->customer_id ? "CUS" . $record->customer_id : "",
                        "contact_number" => $record->phone ?? "",
                        "hub_id" => ucfirst($record->hubid),
                        "payment_status" => $paymentStatus,
                        "vehicle_status" => $vehicleStatus,
                    ];
                }
            }

            return $finalResult;

        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    public static function transactiosExport($request)
    {
        try {
            $finalResult = [];

            $transactions = RiderTransactionHistory::leftJoin('riders', 'rider_transaction_histories.rider_id', 'riders.rider_id')
                ->select(
                    'rider_transaction_histories.transaction_id',
                    'rider_transaction_histories.transaction_ammount',
                    'rider_transaction_histories.created_at',
                    'riders.customer_id',
                    'riders.name',
                    'rider_transaction_histories.transaction_type',
                    'rider_transaction_histories.transaction_notes',
                    DB::raw('CASE
                        WHEN rider_transaction_histories.transaction_mode = 1 THEN "Card"
                        WHEN rider_transaction_histories.transaction_mode = 2 THEN "Wallet"
                        WHEN rider_transaction_histories.transaction_mode = 3 THEN "UPI"
                        WHEN rider_transaction_histories.transaction_mode = 4 THEN "Net Banking"
                        ELSE ""
                    END as transaction_mode'),
                    DB::raw('CASE
                        WHEN rider_transaction_histories.payment_status = 1 THEN "Success"
                        WHEN rider_transaction_histories.payment_status = 2 THEN "Pending"
                        WHEN rider_transaction_histories.payment_status = 3 THEN "Failed"
                        WHEN rider_transaction_histories.payment_status = 4 THEN "Rejected"
                        ELSE ""
                    END as payment_status')
                );

            if (isset($request->tr_id) && !empty($request->tr_id)) {
                $transactions = $transactions->where('rider_transaction_histories.transaction_id', 'LIKE', "%{$request->tr_id}%");
            }
            if (isset($request->cu_id) && !empty($request->cu_id)) {
                $transactions = $transactions->where('riders.customer_id', 'LIKE', $request->cu_id);
            }
            if (isset($request->date) && !empty($request->date)) {
                $transactions = $transactions->whereDate('rider_transaction_histories.created_at', $request->date);
            }
            if (isset($request->p_status) && !empty($request->p_status)) {
                $transactions = $transactions->where('rider_transaction_histories.payment_status', $request->p_status);
            }
            if (isset($request->p_mode) && !empty($request->p_mode)) {
                $transactions = $transactions->where('rider_transaction_histories.payment_status', $request->p_mode);
            }
            if (isset($request->p_type) && !empty($request->p_type)) {
                $transactions = $transactions->where('rider_transaction_histories.transaction_type', $request->p_type);
            }

            $transactions = $transactions->orderBy('created_at', 'DESC')->get();
            if ($transactions->isNotEmpty()) {

                foreach ($transactions as $index => $record) {
                    $transactionType = "Debit";
                    if ($record->transaction_type == 1) {
                        $transactionType = "Credit";
                    }

                    $paymentStatus = "";
                    if ($record->payment_status == 1) {
                        $paymentStatus = "Paid";
                    } elseif ($record->payment_status == 2) {
                        $paymentStatus = "Pending";
                    } elseif ($record->payment_status == 3) {
                        $paymentStatus = "Failed";
                    } elseif ($record->payment_status == 4) {
                        $paymentStatus = "Reject";
                    }

                    $finalResult[] = [
                        "key_id" => $index + 1,
                        "transaction_id" => $record->transaction_id ?? "",
                        "customer_id" => $record->customer_id ? "CUS" . $record->customer_id : "",
                        "transaction_ammount" => $record->transaction_ammount ? "Rs" . $record->transaction_ammount : "",
                        "transaction_type" => $transactionType,
                        "transaction_mode" => $record->transaction_mode ?? "",
                        "payment_status" => $paymentStatus,
                        "created_at" => dateFormat($record->created_at),
                        "transaction_notes" => $record->transaction_notes,
                    ];
                }
            }

            return $finalResult;

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
