<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Models\User;

class ProductController extends AdminAppController
{
    protected $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Get product
    --------------------------------------------------*/
    public function getProducts(Request $request, $param)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('view_inventry', $permission)) {
                $products = $this->product->getProducts($request, $param);
                $products = $products['result']['products'];
                return view('admin.inventory.product_list', compact('products', 'permission'));
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
    Action    : Create product
    --------------------------------------------------*/
    public function createProduct(Request $request, $param )
    {
        try {
            $permission = User::getPermissions();
            $data = $this->product->createProduct($param);
            $rent_cycles = $data['result']['rent_cycles'];
            $ev_types = $data['result']['ev_types'];
            $ev_categories = $data['result']['ev_categories'];
            $hubs = $data['result']['hubs'];
            $battery_types = $data['result']['battery_types'];
            $vehicleStatus = $data['result']['vehicleStatus'];
            $bike_types = $data['result']['bike_types'];
            return view('admin.inventory.create_product', compact('rent_cycles', 'ev_types', 'ev_categories', 'permission','hubs', 'battery_types', 'vehicleStatus', 'bike_types'));
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
    Action    : add product
    --------------------------------------------------*/
    public function addProduct(Request $request)
    {
        try {
            $requiredFields = [
                'title' => 'required',
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
                $product = $this->product->addProduct($request);
                $data = json_encode($product);
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
    Action    : Edit product
    --------------------------------------------------*/
    public function editProduct($slug, $param)
    {
        try {
            $permission = User::getPermissions();
            $data = $this->product->editProduct($slug, $param);
            $product = $data['result']['product'];
            $rent_cycles = $data['result']['rent_cycles'];
            $ev_types = $data['result']['ev_types'];
            $ev_categories = $data['result']['ev_categories'];
            $hubs = $data['result']['hubs'];
            $battery_types = $data['result']['battery_types'];
            $vehicleStatus = $data['result']['vehicleStatus'];
            $bike_types = $data['result']['bike_types'];
            return view('admin.inventory.edit_product', compact('product', 'rent_cycles', 'ev_types', 'ev_categories', 'permission', 'hubs', 'battery_types', 'vehicleStatus', 'bike_types'));
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
    Action    : update product
    --------------------------------------------------*/
    public function updateProduct(Request $request, $slug)
    {
        try {
            $requiredFields = [
                'title' => 'required',
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
                $product = $this->product->updateProduct($request, $slug);
                $data = json_encode($product);
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
