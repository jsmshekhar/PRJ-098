<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Accessories;


class AccessoriesController extends AdminAppController
{

    protected $accessories;

    public function __construct()
    {
        $this->accessories = new Accessories();
    }

   /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Get Accessories
    --------------------------------------------------*/
    public function getAccessories(Request $request)
    {
        try {
            $permission = User::getPermissions();
            //if (Gate::allows('add_product_type', $permission)) {
                $accessories = $this->accessories->getAccessories($request);
                $accessorieses = $accessories['result']['accessories'];
                return view('admin.inventory.accessories', compact('accessorieses', 'permission'));
            //} else {
                //return view('admin.401.401');
           // }
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
