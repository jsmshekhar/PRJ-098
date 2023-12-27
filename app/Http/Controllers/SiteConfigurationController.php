<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\SiteConfiguration;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\AdminAppController;

class SiteConfigurationController extends AdminAppController
{
    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : Site Configuration
    --------------------------------------------------*/
    public function updateCompanyDetail(Request $request)
    {
        try {
            $requiredFields = [
                'company_name' => 'required',
                'company_address' => 'required',
            ];
            if (!$this->checkValidation($request, $requiredFields)) {
                $msg = $this->errorMessage;
                $msg = $msg['errorDetail'];
                $msg = json_encode($msg[0]);
                $msg = json_decode($msg, true);
                $errorText = json_encode($msg['errorMessage'][0]);
                $status = [
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'url' => "",
                    'message' => $errorText,
                ];
                return response()->json($status);
            } else {
                $siteConfiguration = new SiteConfiguration();
                $siteConfig = $siteConfiguration->updateCompanyDetail($request);
                $siteConfigDet = json_encode($siteConfig);
                $siteConfigDet = json_decode($siteConfigDet, true);
                $status = [
                    'status' => $siteConfigDet['original']['status'],
                    'message' => $siteConfigDet['original']['message'],
                ];
                return response()->json($status);
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
