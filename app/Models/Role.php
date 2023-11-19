<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "roles";
    protected $primaryKey = 'role_id';

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Delete Role
    --------------------------------------------------*/
    public function deleteRoleBySlug($slug)
    {
        try {
            Role::where('slug', $slug)->update([
                "status_id" => 3,  // Deleted
            ]);
            $deleteResult = Role::where('slug', $slug);
            $result = $deleteResult->delete();
            if (!empty($result)) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.DELETE'), $result);
            } else {
                return errorResponse(Response::HTTP_OK, Lang::get('messages.DELETE_ERROR'));
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
    Action    : Allow permission tp role
    --------------------------------------------------*/
    public function allowPermission($request)
    {
        try {
            $role_id = !empty($request->role_id) ? $request->role_id : null;
            $permission_id = !empty($request->permission_id) ? $request->permission_id : null;
            $permissionRole = DB::table('permission_roles')->where(['role_id' => $role_id, 'permission_id' => $permission_id])->first();
            if ($permissionRole) {
                $permissionAllow = DB::table('permission_roles')->where(['role_id' => $role_id, 'permission_id' => $permission_id])->delete();
            } else {
                $permissionAllow = DB::table('permission_roles')->insertGetId([
                    "role_id" => $role_id,
                    "permission_id" => $permission_id,
                ]);
            }
            if (!empty($permissionAllow)) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.UPDATE'), $permissionAllow);
            } else {
                return errorResponse(Response::HTTP_OK, Lang::get('messages.INSERT_ERROR'));
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
