<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\Models\EvType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCategory;
use App\Models\User;

class ProductCategoryController extends ApiController
{
    protected $product_category;
    protected $ev_type;

    public function __construct()
    {
        $this->product_category = new ProductCategory();
        $this->ev_type = new EvType();
    }

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Get product category
    --------------------------------------------------*/
    public function getProductCategoryType(Request $request)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('add_product_type', $permission)) {
                $product_category = $this->product_category->getProductCategoryType($request);
                $product_categories = $product_category['result']['product_categories'];
                $ev_types = $product_category['result']['ev_types'];
                return view('admin.inventory.product_category', compact('product_categories', 'ev_types', 'permission'));
            } else {
                return view('admin.401.401');
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

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : add update product category
    --------------------------------------------------*/
    public function addUpdateProductCategory(Request $request)
    {
        try {
            $requiredFields = [
                'product_category_name' => 'required',
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
                $product_category = $this->product_category->addUpdateCategory($request);
                $data = json_encode($product_category);
                $data = json_decode($data, true);
                $status = [
                    'status' => $data['original']['status'],
                    'url' => $data['original']['url'],
                    'message' => $data['original']['message'],
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

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : add update product category
    --------------------------------------------------*/
    public function addUpdateEvType(Request $request)
    {
        try {
            $requiredFields = [
                'ev_type_name' => 'required',
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
                $ev_type = $this->ev_type->addUpdateEvType($request);
                $data = json_encode($ev_type);
                $data = json_decode($data, true);
                $status = [
                    'status' => $data['original']['status'],
                    'url' => $data['original']['url'],
                    'message' => $data['original']['message'],
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
