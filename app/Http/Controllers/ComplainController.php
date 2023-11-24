<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Models\Complain;
use App\Models\ComplainCategory;
use App\Models\User;

class ComplainController extends ApiController
{
    protected $complain;
    protected $complain_category;

    public function __construct()
    {
        $this->complain = new Complain();
        $this->complain_category = new ComplainCategory();
    }

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Get Complains
    --------------------------------------------------*/
    public function getComplains(Request $request)
    {
        try {
            $permission = User::getPermissions();
            $auth = Auth::user();
            if (Gate::allows('view_complaint', $permission)) {
                if($auth->role_id == 0){
                    $complains = Complain::join('complain_categories as cc','complains.complain_category','=','cc.slug')
                        ->whereNull('complains.deleted_at')->orderBy('complains.created_at', 'DESC')
                        ->select('complains.*','cc.category_name')->paginate(25);
                }else{
                    $complains = Complain::join('complain_categories as cc', 'complains.complain_category', '=', 'cc.slug')
                        ->where('complains.user_id', $auth->user_id)
                        ->whereNull('complains.deleted_at')->orderBy('complains.created_at', 'DESC')
                        ->select('complains.*', 'cc.category_name')->paginate(25);
                }
                
                $users = User::where('user_slug', $auth->slug)->whereNull('deleted_at')
                    ->orderBy('first_name', 'DESC')->select('first_name', 'last_name', 'user_id')
                    ->get();
                $categories = ComplainCategory::whereNull('deleted_at')
                    ->orderBy('category_name', 'DESC')->select('category_name', 'slug')
                    ->get();
                return view('admin.complain.complain', compact('complains', 'users', 'categories', 'permission'));
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
    Action    : Complain Status Changed
    --------------------------------------------------*/
    public function complainStatusChanged(Request $request)
    {
        try {
            $statusId = Complain::where('slug', $request->slug)->select('status_id')->first();
            $updateResult = Complain::where('slug', $request->slug)->update([
                "status_id" => $statusId->status_id == 1 ? 2 : 1,
            ]);
            if ($updateResult) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'url' => url('/complain-query-management'),
                    'message' => Lang::get('messages.UPDATE'),
                ];
            } else {
                $status = [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'url' => "",
                    'message' => Lang::get('messages.INSERT_ERROR'),
                ];
            }
            return response()->json($status);
        } catch (\Exception $ex) {
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
    Action    : Complain assignment Changed
    --------------------------------------------------*/
    public function complainAssignmentChanged(Request $request)
    {
        try {
            $user_id = !empty($request->user_id) ? $request->user_id : null;
            $slug = !empty($request->slug) ? $request->slug : null;
            $complain = Complain::where('slug', $slug)->update([
                "user_id" => $user_id,
            ]);
            if ($complain) {
                return redirect()->back();
            } else {
            }
        } catch (\Exception $ex) {
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
    Action    : Get Notification
    --------------------------------------------------*/
    public function getComplainCategories(Request $request)
    {
        try {
            $permission = User::getPermissions();
            $categories = ComplainCategory::where('status_id', 1)->whereNull('deleted_at')->get();
            return view('admin.complain.category', compact('categories', 'permission'));
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
    Action    : Add addComplainCategory
    --------------------------------------------------*/
    public function addUpdateComplainCategories  (Request $request)
   {
        try {
            $category_name = !empty($request->category_name) ? $request->category_name : "";
            $slug = !empty($request->slug) ? $request->slug : "";
            $auth = Auth::user();
            if (!empty($request->slug)) {
                $category = ComplainCategory::where('slug', $slug)->update([
                    "category_name" => $category_name,
                    "user_slug" => $auth->slug,
                    "user_id" => $auth->user_id,
                ]);
            } else {
                $category = ComplainCategory::insertGetId([
                    "slug" => slug(),
                    "category_name" => $category_name,
                    "user_slug" => $auth->slug,
                    "user_id" => $auth->user_id,
                ]);
            }
            if ($category) {
                return redirect()->back();
            } 
        } catch (\Exception $ex) {
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
    Action    : Delete deleteComplainCategory
    --------------------------------------------------*/
    public function deleteComplainCategory($slug)
    {
        try {
            $deleteResult = $this->complain_category->deleteComplainCategorySlug($slug);
            if (!empty($deleteResult)) {
                return redirect()->back()->with('message', Lang::get('messages.DELETE'));
            } else {
                return redirect()->back()->with('message', Lang::get('messages.DELETE_ERROR'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }
}
