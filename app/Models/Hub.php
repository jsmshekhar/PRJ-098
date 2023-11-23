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
            $perPage = env('PER_PAGE');
            if (isset($request->per_page) && $request->per_page > 0) {
                $perPage = $request->per_page;
            }
            $hubs = Hub::where('user_id', $auth->user_id)->whereNull('deleted_at');
            if (isset($request->is_search) && $request->is_search == 1) {
                if (isset($request->hub_id) && !empty($request->hub_id)) {
                    $hubs = $hubs->where('hubId', $request->hub_id);
                }
                if (isset($request->city) && !empty($request->city)) {
                    $hubs = $hubs->where('city', $request->city);
                }
                if (isset($request->hub_capacity) && !empty($request->hub_capacity)) {
                    $hubs = $hubs->where('hub_limit', $request->hub_capacity);
                }
                if (isset($request->vehicle) && !empty($request->vehicle)) {
                    // $hubs = $hubs->where('hub_limit', $request->vehicle);
                }
            }

            $hubs = $hubs->orderBy('created_at', 'DESC')->paginate($perPage);
            $lastHub = Hub::orderBy('hub_id', 'DESC')->first();
            $hubId = "NO101";
            if ($lastHub) {
                $length = 2;
                $lastId = substr($lastHub->hubId, $length);
                $hubLast = $lastId ? (int)$lastId + 1 : 101;
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
