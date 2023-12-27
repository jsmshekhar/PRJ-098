<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use App\Traits\UploadsImageTrait;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, UploadsImageTrait;

    protected $table = "users";
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name', 'email', 'password', 'photo'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Delete User
    --------------------------------------------------*/
    public function deleteUserBySlug($slug)
    {
        try {
            User::where('slug', $slug)->update([
                "status_id" => 4,
            ]);
            $result = User::where('slug', $slug)->delete();
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
    Action    : User Status changed
    --------------------------------------------------*/
    public function userStatusChanged($request)
    {
        try {
            $statusId = User::where('slug', $request->slug)->select('status_id')->first();
            $result = User::where('slug', $request->slug)->update([
                "status_id" => $statusId->status_id == 1 ? 3 : 1,
            ]);
            if (!empty($result)) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.UPDATE'), $result);
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

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : User Role Permission
    --------------------------------------------------*/
    public static function getPermissions()
    {
        $role_id = Auth::user()->role_id;
        $permissions = array();
        if ($role_id != '0') {
            $permission_id = PermissionRole::where('role_id', $role_id)->pluck('permission_id');
            foreach ($permission_id as $key => $id) {
                $name = Permission::where('permission_id', $id)->value('permission_slug');
                array_push($permissions, $name);
            }
        } else {
            $per_names = Permission::pluck('permission_slug');
            foreach ($per_names as $key => $value) {
                array_push($permissions, $value);
            }
        }
        return $permissions;
    }

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : Update User Details
    --------------------------------------------------*/
    public function updateUserDetail($request)
    {
        try {
            $userImage = '';
            $userId = Auth::id();
            $userDetail = User::whereNull('deleted_at')->where('user_id', $userId)->first();

            if (!is_null($userDetail)) {
                if ($request->user_image) {
                    $image = $request->file('user_image');
                    $folder = '/upload/users/';
                    $userImage = $this->uploadImage($image, $folder);
                }
                $userInfo = [
                    "first_name" => $request->user_fname ?? $userDetail->first_name,
                    "last_name" => $request->user_lname ?? "",
                    "phone" => $request->user_phone ?? "",
                    "photo" => $userImage,
                ];
                $userDetail = User::where('user_id', $userId)->update($userInfo);

                if ($userDetail) {
                    $status = [
                        'status' => Response::HTTP_OK,
                        'message' => Lang::get('messages.UPDATE'),
                    ];
                    return response()->json($status);
                }
            }
            $status = [
                'status' => Response::HTTP_BAD_REQUEST,
                'url' => "",
                'message' => Lang::get('messages.UPDATE_ERROR'),
            ];
            return response()->json($status);
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
