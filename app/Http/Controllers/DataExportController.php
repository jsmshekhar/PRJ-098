<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Exports\HubAccessoriesExport;
use App\Exports\HubEmployeeExport;
use App\Exports\HubInventoryExport;
use App\Exports\TransactiosExport;
use App\Exports\UsersExport;
use App\Exports\VehicleExport;
use App\Http\Controllers\AdminAppController;
use App\Models\DataExport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

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
                $refTableId = (int) $request->ref_table_id;
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
                    case config('table.REF_TABLE.HUB_INVENTROY'):
                        $hubInventory = DataExport::hubInventoryExport($request);
                        if (!empty($hubInventory)) {
                            $fileName = 'hub-inventory-' . time() . '.xlsx';
                            return Excel::download(new HubInventoryExport($hubInventory), $fileName);
                        } else {
                            return redirect()->back();
                        }
                        break;
                    case config('table.REF_TABLE.HUB_EMPLOYEE'):
                        $hubEmployee = DataExport::hubEmployeeExport($request);
                        if (!empty($hubEmployee)) {
                            $fileName = 'hub-employee-' . time() . '.xlsx';
                            return Excel::download(new HubEmployeeExport($hubEmployee), $fileName);
                        } else {
                            return redirect()->back();
                        }
                        break;
                    case config('table.REF_TABLE.HUB_ACCESSORIES'):
                        $hubAccessories = DataExport::hubAccessoriesExport($request);
                        if (!empty($hubAccessories)) {
                            $fileName = 'hub-accessories-' . time() . '.xlsx';
                            return Excel::download(new HubAccessoriesExport($hubAccessories), $fileName);
                        } else {
                            return redirect()->back();
                        }
                        break;
                    case config('table.REF_TABLE.USERS'):
                        $userList = DataExport::usersExport($request);
                        if (!empty($userList)) {
                            $fileName = 'user-list-' . time() . '.xlsx';
                            return Excel::download(new UsersExport($userList), $fileName);
                        } else {
                            return redirect()->back();
                        }
                        break;
                    case config('table.REF_TABLE.VEHICLES'):
                        $vehicleList = DataExport::vehicleExport($request);
                        if (!empty($vehicleList)) {
                            $fileName = 'vehicle-list-' . time() . '.xlsx';
                            return Excel::download(new VehicleExport($vehicleList), $fileName);
                        } else {
                            return redirect()->back();
                        }
                        break;
                    case config('table.REF_TABLE.TRANSACTIONS'):
                        $transactionList = DataExport::transactiosExport($request);
                        if (!empty($transactionList)) {
                            $fileName = 'transaction-list-' . time() . '.xlsx';
                            return Excel::download(new TransactiosExport($transactionList), $fileName);
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
