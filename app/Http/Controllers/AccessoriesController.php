<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAppController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Models\Accessories;
use App\Traits\UploadsImageTrait;

class AccessoriesController extends AdminAppController
{
    use UploadsImageTrait;
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
            $accessories_categories = $accessories['result']['accessories_categories'];
                return view('admin.inventory.accessories', compact('accessorieses', 'permission', 'accessories_categories'));
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

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Add Update Accessories
    --------------------------------------------------*/
    public function addUpdateAccessories(Request $request)
    {
        try {
            $title = !empty($request->title) ? $request->title : "";
            $no_of_item = !empty($request->no_of_item) ? $request->no_of_item : "";
            $price = !empty($request->price) ? $request->price : "";
            $accessories_category_id = !empty($request->accessories_category) ? $request->accessories_category : "";
            $slug = !empty($request->slug) ? $request->slug : "";
            $auth = Auth::user(); 
            $accessories_image = "";
            
            if ($request->image) {
                $image = $request->file('image');
                $folder = '/upload/accessories/';
                $accessories_image = $this->uploadImage($image, $folder);
            }
            if ($slug) {
                if ($accessories_image) {
                    $accessories = Accessories::where('slug', $slug)->update([
                        "title" => $title,
                        "no_of_item" => $no_of_item,
                        "accessories_category_id" => $accessories_category_id,
                        "price" => $price,
                        "image" => $accessories_image,
                        "updated_by" => $auth->user_id,
                    ]);
                } else {
                    $accessories = Accessories::where('slug', $slug)->update([
                        "title" => $title,
                        "no_of_item" => $no_of_item,
                        "accessories_category_id" => $accessories_category_id,
                        "price" => $price,
                        "updated_by" => $auth->user_id,
                    ]);
                }
            }else{
                $unique = Accessories::where('accessories_category_id', $accessories_category_id)->first();
                $slug = slug();
                if($unique){
                    $status = [
                        'status' => Response::HTTP_OK,
                        'url' => route('accessories'),
                        'message' => Lang::get('messages.ALREADY_FOUND'),
                    ];
                    return response()->json($status);
                }else{
                    $accessories = Accessories::insertGetId([
                        "slug" => $slug,
                        "title" => $title,
                        "no_of_item" => $no_of_item,
                        "accessories_category_id" => $accessories_category_id,
                        "price" => $price,
                        "image" => !empty($accessories_image) ? $accessories_image : "",
                        "user_id" => $auth->user_id,
                        "user_slug" => $auth->slug,
                        "created_by" => $auth->user_id,
                    ]);
                }
                
            }
            
            if ($accessories) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'url' => route('accessories'),
                    'message' => !empty($slug) ? Lang::get('messages.UPDATE') : Lang::get('messages.INSERT'),
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
