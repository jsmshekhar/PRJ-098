<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AdminAppController;

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
                        $finalResult = [];
                        $fileName = 'customer-list-' . time() . '.xlsx';
                        return Excel::download(new CustomerExport($finalResult), $fileName);
                        break;
                    default:
                        return 10;
                }
            } else {
                return "Outside if";
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
