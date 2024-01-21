<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;

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
            $notifications = Notification::where('created_by', $auth->user_id)->orWhere('created_by', '!=', $auth->user_id)->whereNull('deleted_at')->orderBy('created_at', 'DESC')->paginate(25);
            foreach ($notifications as $key => $notification) {
                $notification->param = strtolower($notification->notification_type);
            }
            $np = config('constants.NOTIFICATION_PARAMETER');
            $drtn = config('constants.DISTANCE_REMAINING_TO_NOTIFY');
            $dayrtn = config('constants.DAYS_REMAINING_TO_NOTIFY');
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
            $userBase = config('constants.USER_BASE_NOTIFICATION');

            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['parameters' => $np, 'distance' => $drtn, 'days' => $dayrtn, 'user_base' => $userBase]);
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
            $userBase = config('constants.USER_BASE_NOTIFICATION');
            return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['parameters' => $np, 'distance' => $drtn, 'days' => $dayrtn, 'notification' => $notification, 'user_base' => $userBase]);
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

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Schedule Notification
    --------------------------------------------------*/
    public static function sendInstantNotification()
    {
        try {
            $notifications = Notification::where('notification_type', 'Manual')->where(['status_id' => 1, 'notification_parameter' => 4, 'notification_status' => 2])->get();
            if (!empty($notifications)) {
                foreach ($notifications as $notification) {
                    $notificationUserBase = (int)$notification->notification_user_based;
                    $notificationId = $notification->notification_id;
                    $title = $notification->title;
                    $description = $notification->description;
                    // Mark as in Queue
                    Notification::where('notification_id', $notificationId)->update(['notification_status' => 3]);
                    switch ($notificationUserBase) {
                        case 2: // MOBILIZED
                            $riderIds = RiderOrder::leftJoin('products', 'products.product_id', '=', 'rider_orders.mapped_vehicle_id')
                                ->leftJoin('riders', 'riders.rider_id', '=', 'rider_orders.rider_id')
                                ->where(['rider_orders.status_id' => 1, 'riders.status_id' => 1, 'products.ev_status' => 1])
                                ->pluck('riders.rider_id')->toArray();
                            if (!empty($riderIds)) {
                                $data = ['title' => $title, 'description' => $description];
                                Notification::sendPushNotification($riderIds, $notificationId, $data);
                            }
                            // Mark as in Send
                            Notification::where('notification_id', $notificationId)->update(['notification_status' => 1]);
                            break;
                        case 3: // IMMOBILIZED
                            $riderIds = RiderOrder::leftJoin('products', 'products.product_id', '=', 'rider_orders.mapped_vehicle_id')
                                ->leftJoin('riders', 'riders.rider_id', '=', 'rider_orders.rider_id')
                                ->where(['rider_orders.status_id' => 1, 'riders.status_id' => 1, 'products.ev_status' => 2])
                                ->pluck('riders.rider_id')->toArray();
                            if (!empty($riderIds)) {
                                $data = ['title' => $title, 'description' => $description];
                                Notification::sendPushNotification($riderIds, $notificationId, $data);
                            }
                            // Mark as in Send
                            Notification::where('notification_id', $notificationId)->update(['notification_status' => 1]);
                            break;
                        case 4: // EV_RETURN_REQUEST
                            $riderIds = ReturnExchange::where(['request_for' => 1, 'status_id' => 2])->pluck('rider_id')->toArray();
                            if (!empty($riderIds)) {
                                $data = ['title' => $title, 'description' => $description];
                                Notification::sendPushNotification($riderIds, $notificationId, $data);
                            }
                            // Mark as in Send
                            Notification::where('notification_id', $notificationId)->update(['notification_status' => 1]);
                            break;
                        case 5: // EV_SERVICE_REQUIRED
                            /*$riderIds =  EvServiceRequset::where(['request_for' => 1, 'status_id' => 2])->pluck('rider_id')->toArray();
                            if (!empty($riderIds)) {
                                $data = ['title' => $title, 'description' => $description];
                                Notification::sendPushNotification($riderIds, $notificationId, $data);
                            }
                            // Mark as in Send
                            Notification::where('notification_id', $notificationId)->update(['notification_status' => 1]);
                            */
                            break;
                        case 7: // ALL
                            $riderIds =  Rider::where(['status_id' => 2])->where('kyc_status', '!=', 3)->pluck('rider_id')->toArray();
                            if (!empty($riderIds)) {
                                $data = ['title' => $title, 'description' => $description];
                                Notification::sendPushNotification($riderIds, $notificationId, $data);
                            }
                            // Mark as in Send
                            Notification::where('notification_id', $notificationId)->update(['notification_status' => 1]);
                            break;
                    }
                }
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

    public static function sendPushNotification($riderIds = [], $notificationId = null, $data = [])
    {
        $riderTokes = RiderToken::whereIn('rider_id', $riderIds)->where('status_id', 1)->get();
        if (!empty($riderTokes)) {
            foreach ($riderTokes as $tokes) {
                $deviceToken = $tokes->device_token ?? null;
                $deviceType = $tokes->device_type ?? null;
                $riderId = $tokes->rider_id ?? null;
                $isNotificationSent = false;
                if ($deviceType == 1 && !is_null($deviceToken)) {
                    $isNotificationSent = true;
                    // Need to send notification for Android Users

                }
                if ($deviceType == 2 && !is_null($deviceToken)) {
                    $isNotificationSent = true;
                    // Need to send notification for iOS Users
                }

                if ($isNotificationSent) {
                    // Need to store in rider notification table
                    $isAvail = RiderNotification::where(['rider_id' => $riderId, 'notification_id' => $notificationId])->first();
                    if (is_null($isAvail)) {
                        $riderNotification = [
                            'slug' => slug(),
                            'rider_id' => $riderId,
                            'notification_id' => $notificationId,
                            'title' => $data['title'] ?? '',
                            'description' => $data['description'] ?? '',
                            'status_id' => 1,
                        ];
                        DB::table('rider_notifications')->insertGetId($riderNotification);
                    }
                }
            }
        }
    }
}
