<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Api\ApiController;
use App\Notifications\SetPasswordNotification;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class RoleUserController extends ApiController
{
    use SendsPasswordResetEmails;
    protected $role;
    protected $user;

    public function __construct()
    {
        $this->role = new Role();
        $this->user = new User();
    }

    #Role Management Functions

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Add Index
    --------------------------------------------------*/
    public function getRoles(Request $request)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('view_role', $permission)) {
                $roles = Role::where('user_id', Auth::user()->user_id)->whereNull('deleted_at')->orderBy('role_id', 'desc')->get();
                foreach ($roles as $key => $value) {
                    $value->roleUsers = User::where('role_id', $value->role_id)->count();
                }
                $permissions = DB::table('modules')->select('module_id', 'slug', "name")->distinct()->get();
                foreach ($permissions as $row) {
                    $permission_allow = DB::table('permissions')->where('module_slug', $row->slug)->get();
                    $row->sub_module = $permission_allow;
                }
                return view('admin.role.index', compact('roles', 'permission_allow', 'permissions', 'permission'));
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
    Action    : Add Role
    --------------------------------------------------*/
    public function addUpdateRole(Request $request)
    {
        try {
            $role_name = !empty($request->role_name) ? $request->role_name : "";
            $slug = !empty($request->slug) ? $request->slug : "";
            $auth = Auth::user();
            if (!empty($request->slug)) {
                $roleId = Role::where('slug', $slug)->update([
                    "name" => $role_name,
                    "user_slug" => $auth->slug,
                    "user_id" => $auth->user_id,
                ]);
            } else {
                $roleId = Role::insertGetId([
                    "slug" => slug(),
                    "name" => $role_name,
                    "user_slug" => $auth->slug,
                    "user_id" => $auth->user_id,
                ]);
            }
            if ($roleId) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'url' => url('/roles'),
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
    Action    : Delete Role
    --------------------------------------------------*/
    public function deleteRole($slug)
    {
        try {
            $deleteResult = $this->role->deleteRoleBySlug($slug);
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

    # User Management Functions

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Add Index
    --------------------------------------------------*/
    public function getUsers(Request $request)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('view_user', $permission)) {
                $users = User::select('users.*', 'roles.name as role_name')
                    ->where('users.user_slug', Auth::user()->slug)
                    ->where('users.role_id', '!=', 0)
                    ->whereNull('users.deleted_at')
                    ->orderBy('users.created_at', 'DESC')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.role_id')
                    ->paginate(15);

                $roles = Role::where('user_id', Auth::user()->user_id)->whereNull('deleted_at')->get();
                return view('admin.user.index', compact('users', 'roles', 'permission'));
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
    Action    : Add  update User
    --------------------------------------------------*/
    public function addUpdateUser(Request $request)
    {
        try {
            $first_name = !empty($request->first_name) ? $request->first_name : "";
            $last_name = !empty($request->last_name) ? $request->last_name : "";
            $email = !empty($request->email) ? $request->email : "";
            $phone = !empty($request->phone) ? $request->phone : "";
            $role_id = !empty($request->role_id) ? $request->role_id : "";
            $slug = !empty($request->slug) ? $request->slug : "";
            $last_empId = User::latest()->select('emp_id')->where('users.role_id', '!=', 0)->first();
            $auth = Auth::user();
            if (!empty($request->slug)) {
                $userId = User::where('slug', $slug)->update([
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "email" => $email,
                    "phone" => $phone,
                    "role_id" => $role_id,
                ]);
            } else {
                $slug = slug();
                $token = Str::random(60); // generate a random token
                $userId = User::insertGetId([
                    "slug" => $slug,
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "email" => $email,
                    "phone" => $phone,
                    "role_id" => $role_id,
                    "user_slug" => $auth->slug,
                    "emp_id" => $last_empId ? $last_empId->emp_id + 1 : 101,
                    "password" => Hash::make($slug),
                    "set_password_token" => $token,
                ]);
                $user = User::where('user_id', $userId)->first();
                // $user->notify(new SetPasswordNotification($token));
            }
            if ($userId) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'url' => url('/users'),
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
    Action    : Delete User
    --------------------------------------------------*/
    public function deleteUser($slug)
    {
        try {
            $deleteResult = $this->user->deleteUserBySlug($slug);
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

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : User Status Changed
    --------------------------------------------------*/
    public function userStatusChanged(Request $request)
    {
        try {
            $userStatus = $this->user->userStatusChanged($request);
            $status = [
                'status' => Response::HTTP_OK,
                'url' => url('/users'),
                'message' => Lang::get('messages.UPDATE'),
            ];
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
    Action    : allow pwermission to Role
    --------------------------------------------------*/
    public function allowPermission(Request $request)
    {
        try {
            $userStatus = $this->role->allowPermission($request);
            $status = [
                'status' => Response::HTTP_OK,
                'url' => url('/user-role-permission'),
                'message' => Lang::get('messages.UPDATE'),
            ];
            return response()->json($status);
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }

        $permission = $request->permission_id;
        $role_id = $request->role_id;
        $delete = DB::table('permission_roles')->where('role_id', $role_id)->delete();
        if ($permission > 0) {
            for ($i = 0; $i < sizeof($permission); $i++) {
                $data = array();
                $data['permission_id'] = $permission[$i];
                $data['role_id']   = $role_id;
                $query_insert = DB::table('permission_roles')->insert($data);
            }

            if ($query_insert) {
                return redirect()->back()->with('msg', 'Permission provided successfully');
            }
        } else {
            return redirect()->back()->with('msg', 'Permission removed successfully');
        }
    }
}
