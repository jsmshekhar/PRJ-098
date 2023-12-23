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
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Controllers\AdminAppController;

class RoleUserController extends AdminAppController
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
                $roles = Role::where('created_by', Auth::user()->user_id)->whereNull('deleted_at')->orderBy('role_id', 'desc')->get();
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
                    "created_by" => $auth->user_id,
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
            $perPage = env('PER_PAGE');
            if (isset($request->per_page) && $request->per_page > 0) {
                $perPage = $request->per_page;
            }
            $auth = Auth::user();
            if (Gate::allows('view_user', $permission)) {
                $users = User::select('users.*', 'roles.name as role_name')
                    ->where(function ($query) {
                        $query->where('users.hub_id', Auth::user()->hub_id)
                            ->where('users.role_id', '!=', 0)
                            ->where('users.user_id', '!=', Auth::user()->user_id);
                    })
                    ->orWhere(function ($query) {
                        $query->where('users.created_by', Auth::user()->user_id)
                            ->where('users.role_id', '!=', 0)
                            ->where('users.user_id', '!=', Auth::user()->user_id);
                    })
                    ->whereNull('users.deleted_at')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.role_id');

                if (isset($request->is_search) && $request->is_search == 1) {
                    if (isset($request->emp_id) && !empty($request->emp_id)) {
                        $users = $users->where('users.emp_id', $request->emp_id);
                    }
                    if (isset($request->first_name) && !empty($request->first_name)) {
                        $users = $users->where('users.first_name', 'LIKE', "%{$request->first_name}%");
                    }
                    if (isset($request->last_name) && !empty($request->last_name)) {
                        $users = $users->where('users.last_name', 'LIKE', "%{$request->last_name}%");
                    }
                    if (isset($request->email) && !empty($request->email)) {
                        $users = $users->where('users.email', 'LIKE', "%{$request->email}%");
                    }
                    if (isset($request->phone) && !empty($request->phone)) {
                        $users = $users->where('users.phone', 'LIKE', "%{$request->phone}%");
                    }
                    if (isset($request->role) && !empty($request->role)) {
                        $users = $users->where('users.role_id', $request->role);
                    }
                }
                $users = $users->orderBy('users.created_at', 'DESC')->paginate($perPage);
                $roles = Role::where('user_id', Auth::user()->user_id)->whereNull('deleted_at')->get();
                $hubs = DB::table('hubs')->whereNull('deleted_at')->where('status_id', 1)->select('hub_id', 'city')->get();
                return view('admin.user.index', compact('users', 'roles', 'hubs', 'permission'));
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
            $hub_id = !empty($request->hub_id) ? $request->hub_id : "";
            $password = !empty($request->password) ? $request->password : "";
            $last_empId = User::latest()->select('emp_id')->where('users.role_id', '!=', 0)->first();
            $auth = Auth::user();
            if (!empty($request->slug)) {
                if($password){
                    $userId = User::where('slug', $slug)->update([
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "email" => $email,
                        "phone" => $phone,
                        "role_id" => $role_id,
                        "hub_id" => $hub_id,
                        "password" => Hash::make($password),
                    ]);
                }else{
                    $userId = User::where('slug', $slug)->update([
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "email" => $email,
                        "phone" => $phone,
                        "role_id" => $role_id,
                        "hub_id" => $hub_id,
                    ]);
                }
                
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
                    "hub_id" => $hub_id,
                    "user_slug" => $auth->slug,
                    "emp_id" => $last_empId ? $last_empId->emp_id + 1 : 101,
                    "password" => Hash::make($password),
                    "set_password_token" => $token,
                    "created_by" => $auth->user_id,
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
