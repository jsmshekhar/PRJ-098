<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Models\Complain;
use App\Models\ComplainCategory;
use App\Models\User;
use App\Http\Controllers\AdminAppController;

class ComplainController extends AdminAppController
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
            $perPage = env('PER_PAGE');
            if (isset($request->per_page) && $request->per_page > 0) {
                $perPage = $request->per_page;
            }
            if (Gate::allows('view_complaint', $permission)) {
                $complains = Complain::leftJoin('complain_categories as cc', 'complains.complain_category', '=', 'cc.slug')
                        ->whereNull('complains.deleted_at')
                        ->select('complains.*', 'cc.category_name');
                if (isset($request->is_search) && $request->is_search == 1) {
                    if (isset($request->complain_id) && !empty($request->complain_id)) {
                        $complains = $complains->where('complains.complain_number', 'LIKE', "%{$request->complain_id}%");
                    }
                    if (isset($request->name) && !empty($request->name)) {
                        $complains = $complains->where('complains.name', 'LIKE', "%{$request->name}%");
                    }
                    if (isset($request->email) && !empty($request->email)) {
                        $complains = $complains->where('complains.email', 'LIKE', "%{$request->email}%");
                    }
                    if (isset($request->phone) && !empty($request->phone)) {
                        $complains = $complains->where('complains.phone', 'LIKE', "%{$request->phone}%");
                    }
                    if (isset($request->category) && !empty($request->category)) {
                        $complains = $complains->where('complains.complain_category', $request->category);
                    }
                    if (isset($request->status) && !empty($request->status)) {
                        $complains = $complains->where('complains.status_id', $request->status);
                    }
                    if (isset($request->date) && !empty($request->date)) {
                        $complains = $complains->whereDate('complains.created_at', $request->date);
                    }
                }
                $complains = $complains->orderBy('complains.created_at', 'DESC')->paginate($perPage);
                $role = DB::table('users')
                    ->select('roles.name', 'users.role_id', 'users.hub_id')
                    ->Join('roles', 'users.role_id', '=', 'roles.role_id')
                    ->where('users.role_id', '!=', 0);
                if ($auth->role_id != 0) {
                    $role->where('users.hub_id', $auth->hub_id);
                }
                $roles = $role->whereNull('users.deleted_at')->whereNull('roles.deleted_at')->distinct()->get();
                $categories = ComplainCategory::whereNull('deleted_at')->get();
                $compalinStatus = config('constants.COMPLAIN_STATUS');
                return view('admin.complain.complain', compact('complains', 'roles', 'categories', 'compalinStatus', 'permission'));
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
            $role_id = !empty($request->role_id) ? $request->role_id : null;
            $slug = !empty($request->slug) ? $request->slug : null;
            $complain = Complain::where('slug', $slug)->update([
                "role_id" => $role_id,
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
            $auth = Auth::user();
            $permission = User::getPermissions();
            $categories = ComplainCategory::leftJoin('roles', 'complain_categories.role_id', '=', 'roles.role_id')
                ->where('complain_categories.status_id', 1)
                ->whereNull('complain_categories.deleted_at')
                ->select('complain_categories.*', 'roles.name as role_name')
                ->get();
            $roles = DB::table('users')
                ->select('roles.name', 'users.role_id', 'users.hub_id')
                ->rightJoin('roles', 'users.role_id', '=', 'roles.role_id')
                ->where('users.role_id', '!=', 0)
                ->whereNull('users.deleted_at')->whereNull('roles.deleted_at')->distinct()->get();
            foreach ($roles as $key => $value) {
                $value->city = DB::table('hubs')->where('hub_id', $value->hub_id)->value('city');
            }
            return view('admin.complain.category', compact('categories', 'roles', 'permission'));
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
    public function addUpdateComplainCategories(Request $request)
    {
        try {
            $category_name = !empty($request->category_name) ? $request->category_name : "";
            $slug = !empty($request->slug) ? $request->slug : "";
            $role_id = !empty($request->role_id) ? $request->role_id : "";
            $auth = Auth::user();
            if (!empty($request->slug)) {
                $category = ComplainCategory::where('slug', $slug)->update([
                    "category_name" => $category_name,
                    "user_slug" => $auth->slug,
                    "user_id" => $auth->user_id,
                    "role_id" => $role_id,
                ]);
            } else {
                $category = ComplainCategory::insertGetId([
                    "slug" => slug(),
                    "category_name" => $category_name,
                    "user_slug" => $auth->slug,
                    "user_id" => $auth->user_id,
                    "role_id" => $role_id,
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
