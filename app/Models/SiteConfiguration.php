<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Models\Hub;
use App\Traits\UploadsImageTrait;
use App\Models\EvType;
use Nette\Utils\Image;

class SiteConfiguration extends Model
{
    use HasFactory, UploadsImageTrait;

    protected $table = "site_configuration";
    protected $primaryKey = 'site_configuration_id';

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : Update Company Details
    --------------------------------------------------*/
    public function updateCompanyDetail($request)
    {
        try {
            $companyLogo = '';
            $siteDetails = SiteConfiguration::whereNull('deleted_at')->first();

            if ($request->company_logo) {
                $image = $request->file('company_logo');
                $folder = '/upload/settengs/';
                $companyLogo = $this->uploadImage($image, $folder);
            }
            $companyDetail = [
                "company_name" => $request->company_name ?? "",
                "company_address" => $request->company_name ?? "",
                "company_logo" => $companyLogo,
            ];

            if (!is_null($siteDetails)) {
                $slug = $siteDetails->slug;
                $companyDetail = SiteConfiguration::where('slug', $slug)->update($companyDetail);
            } else {
                $companyDetail['slug'] = slug();
                $companyDetail = SiteConfiguration::insertGetId($companyDetail);
            }
            if ($companyDetail) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'message' => Lang::get('messages.UPDATE'),
                ];
                return response()->json($status);
            } else {
                $status = [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'url' => "",
                    'message' => Lang::get('messages.INSERT_ERROR'),
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
