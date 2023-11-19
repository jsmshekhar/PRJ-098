<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class Notification extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "notifications";
    protected $primaryKey = 'notification_id';

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : get notification
    --------------------------------------------------*/
    public function getNotifications($request)
    {
        try {
            $auth = Auth::user();
            $notifications = Notification::where('user_id', $auth->user_id)->whereNull('deleted_at')->orderBy('created_at', 'DESC')->paginate(25);
            foreach ($notifications as $key => $notification) {
                $notification->param = strtolower($notification->notification_type);
            }
            $np = config('constants.NOTIFICATION_PARAMETER');
            $drtn = config('constants.DISTANCE_REMAINING_TO_NOTIFY');
            $dayrtn = config('constants.DAYS_REMAINING_TO_NOTIFY');
            foreach ($notifications as $key => $value) {
                foreach ($np as $key => $nps) { // notification_parameter
                    if ($value->notification_parameter == $nps) {
                        $value->notification_parameter = str_replace("_", " ", $key);
                        $value->notification_parameter_value = $nps;
                        break;
                    }
                }
                foreach ($drtn as $key => $drt) { // distance_remaining
                    if ($value->distance_remaining == $drt && $value->distance_remaining != null) {
                        $value->distance_remaining = str_replace("_", " ", $key);
                        break;
                    }
                }
                foreach ($dayrtn as $key => $dayrem) { // days_remaining
                    if ($value->days_remaining == $dayrem && $value->days_remaining != null) {
                        $value->days_remaining = str_replace("_", " ", $key);
                        break;
                    }
                }
            }
            if (count($notifications) > 0) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['notifications' => $notifications]);
            } else {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['notifications' => []]);
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
    Action    : get hubs
    --------------------------------------------------*/
    public function createNotification($param)
    {
        try {
            $np = config('constants.NOTIFICATION_PARAMETER');
            $drtn = config('constants.DISTANCE_REMAINING_TO_NOTIFY');
            $dayrtn = config('constants.DAYS_REMAINING_TO_NOTIFY');
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['parameters' => $np, 'distance' => $drtn, 'days' => $dayrtn]);
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
    Action    : get hubs
    --------------------------------------------------*/
    public function editNotification($param, $slug)
    {
        try {
            $notification = Notification::where('slug', $slug)->whereNull('deleted_at')->first();
            $np = config('constants.NOTIFICATION_PARAMETER');
            $drtn = config('constants.DISTANCE_REMAINING_TO_NOTIFY');
            $dayrtn = config('constants.DAYS_REMAINING_TO_NOTIFY');
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['parameters' => $np, 'distance' => $drtn, 'days' => $dayrtn, 'notification' => $notification]);
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
    Action    : Delete Notification
    --------------------------------------------------*/
    public function deleteNotificationBySlug($slug)
    {
        try {
            $deleteResult = Notification::where('slug', $slug);
            $result = $deleteResult->delete();
            Notification::where('slug', $slug)->update([
                "status_id" => 4,
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
}
