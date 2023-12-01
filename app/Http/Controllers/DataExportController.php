<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AdminAppController;
use App\Models\DataExport;

class DataExportController extends AdminAppController
{


    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : Export Data
    --------------------------------------------------*/
    public function dataExport(Request $request)
    {
        try {
            if (isset($request->ref_table_id) && !is_null($request->ref_table_id) && !empty($request->ref_table_id)) {
                $refTableId = (int)$request->ref_table_id;
                switch ($refTableId) {
                    case config('table.REF_TABLE.RIDER'):
                        $customerList = DataExport::customerExport($request);
                        if (!empty($customerList)) {
                            $fileName = 'customer-list-' . time() . '.xlsx';
                            return Excel::download(new CustomerExport($customerList), $fileName);
                        } else {
                            return redirect()->back();
                        }
                        break;
                    default:
                        return redirect()->back();
                }
            } else {
                return redirect()->back();
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
}
