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
use App\Models\User;

class ComplainController extends ApiController
{
    protected $complain;

    public function __construct()
    {
        $this->complain = new Complain();
    }

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Get Complains
    --------------------------------------------------*/
    public function getComplains(Request $request)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('view_complaint', $permission)) {
                $complains = Complain::whereNull('deleted_at')->orderBy('created_at', 'DESC')->paginate(25);
                return view('admin.complain.complain', compact('complains', 'permission'));
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
}
