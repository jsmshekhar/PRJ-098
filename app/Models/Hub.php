<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class Hub extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "hubs";
    protected $primaryKey = 'hub_id';

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : get hubs
    --------------------------------------------------*/
    public function getHubs($request)
    {
        try {
            $auth = Auth::user();
            $hubs = Hub::where('user_id', $auth->user_id)->whereNull('deleted_at')->orderBy('created_at', 'DESC')->paginate(15);
            $lastHub = Hub::orderBy('hub_id', 'DESC')->first();
            $hubId = "101";
            if ($lastHub) {
                $length = 2;
                //     $firstId = substr($lastHub->hubId, 0, $length);
                $lastId = substr($lastHub->hubId, $length);
                $hubLast = $lastId ? (int)$lastId + 1 : 101;
                //     $hubId = $firstId . (string)$hubLast;
                $hubId = (string)$hubLast;
            }
            if (count($hubs) > 0) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['hubs' => $hubs, 'hubId' => $hubId]);
            } else {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['hubs' => [], 'hubId' => $hubId]);
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
    Action    : view hub
    --------------------------------------------------*/
    public function viewHub($slug)
    {
        try {
            $hub = Hub::where('slug', $slug)->whereNull('deleted_at')->first();
            if ($hub) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), $hub);
            } else {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), []);
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
    Action    : Delete Hub
    --------------------------------------------------*/
    public function deleteHubBySlug($slug)
    {
        try {
            $deleteResult = Hub::where('slug', $slug);
            $result = $deleteResult->delete();
            Hub::where('slug', $slug)->update([
                "status_id" => 3,
            ]);

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
    Action    : Hub Status changed
    --------------------------------------------------*/
    public function hubStatusChanged($request)
    {
        try {
            $statusId = Hub::where('slug', $request->slug)->select('status_id')->first();
            $result = Hub::where('slug', $request->slug)->update([
                "status_id" => $statusId->status_id == 1 ? 2 : 1,
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
}
