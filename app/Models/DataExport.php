<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class DataExport extends Model
{
    public static function  customerExport($request)
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
}
