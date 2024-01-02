<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Notification;
use App\Models\User;
use App\Http\Controllers\AdminAppController;

class NotificationController extends AdminAppController
{
    protected $notification;

    public function __construct()
    {
        $this->notification = new Notification();
    }

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Get Notification
    --------------------------------------------------*/
    public function getNotifications(Request $request)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('view_notification', $permission)) {
                $notification = $this->notification->getNotifications($request);
                $notifications = $notification['result']['notifications'];
                return view('admin.notification.list', compact('notifications', 'permission'));
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
    Action    : Create Notification
    --------------------------------------------------*/
    public function createNotification($param)
    {
        try {
            $permission = User::getPermissions();
            $notification = $this->notification->createNotification($param);
            $parameters = $notification['result']['parameters'];
            $distance = $notification['result']['distance'];
            $days = $notification['result']['days'];
            $user_base = $notification['result']['user_base'];
            
            return view('admin.notification.create', compact('parameters', 'distance', 'days', 'user_base', 'permission'));
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
    Action    : Add Notification
    --------------------------------------------------*/
    public function addNotification(Request $request)
    {
        try {
            $distance_remaining = null;
            $days_remaining = null;
            $penalty_charge_text = null;
            $penalty_charge = null;
            $is_send_charge = 0;
            $notification_user_based = null;
            $schedule_date = null;
            $title = !empty($request->title) ? $request->title : null;
            $description = !empty($request->description) ? $request->description : null;
            $notification_parameter = !empty($request->notification_parameter) ? $request->notification_parameter : null;
            $distance_remaining = !empty($request->distance_remaining) ? $request->distance_remaining : null;
            $days_remaining = !empty($request->days_remaining) ? $request->days_remaining : null;
            $penalty_charge_text = !empty($request->penalty_charge_text) ? $request->penalty_charge_text : null;
            $penalty_charge = !empty($request->penalty_charge) ? $request->penalty_charge : null;
            $is_send_charge = !empty($request->is_send_charge) ? $request->is_send_charge : 0;
            $notification_user_based = !empty($request->notification_user_based) ? $request->notification_user_based : null;
            $schedule_date = !empty($request->schedule_date) ? $request->schedule_date : null;
            $notification_type = !empty($request->notification_type) ? $request->notification_type : null;
            $status_id = !empty($request->status_id) ? $request->status_id : null;
            $auth = Auth::user();

            $slug = slug();
            $notificationId = Notification::insertGetId([
                "slug" => $slug,
                "title" => $title,
                "description" => $description,
                "notification_type" => $notification_type,
                "notification_parameter" => $notification_parameter,
                "notification_user_based" => $notification_user_based,
                "distance_remaining" => $distance_remaining,
                "days_remaining" => $days_remaining,
                "penalty_charge" => $penalty_charge,
                "penalty_charge_text" => $penalty_charge_text,
                "is_send_charge" => $is_send_charge == 'on' ? 1 : 0,
                "schedule_date" => $schedule_date,
                "status_id" => $status_id,
                "user_id" => $auth->user_id,
                "user_slug" => $auth->slug,
                "created_by" => $auth->user_id,
            ]);

            if ($notificationId) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'url' => route('notifications'),
                    'message' => Lang::get('messages.INSERT'),
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
    Action    : Edit Notification
    --------------------------------------------------*/
    public function editNotification($param, $slug)
    {
        try {
            $permission = User::getPermissions();
            $notification = $this->notification->editNotification($param, $slug);
            $user_base = $notification['result']['user_base'];
            $parameters = $notification['result']['parameters'];
            $distance = $notification['result']['distance'];
            $days = $notification['result']['days'];
            $notification = $notification['result']['notification'];
            return view('admin.notification.edit', compact('notification', 'parameters', 'distance', 'days', 'user_base', 'permission'));
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
    Action    : Update Notification
    --------------------------------------------------*/
    public function UpdateNotification(Request $request, $slug)
    {
        try {
            $distance_remaining = null;
            $days_remaining = null;
            $penalty_charge_text = null;
            $penalty_charge = null;
            $is_send_charge = 0;
            $notification_user_based = null;
            $schedule_date = null;
            $title = !empty($request->title) ? $request->title : null;
            $description = !empty($request->description) ? $request->description : null;
            $notification_parameter = !empty($request->notification_parameter) ? $request->notification_parameter : null;
            $distance_remaining = !empty($request->distance_remaining) ? $request->distance_remaining : null;
            $days_remaining = !empty($request->days_remaining) ? $request->days_remaining : null;
            $penalty_charge_text = !empty($request->penalty_charge_text) ? $request->penalty_charge_text : null;
            $penalty_charge = !empty($request->penalty_charge) ? $request->penalty_charge : null;
            $is_send_charge = !empty($request->is_send_charge) ? $request->is_send_charge : 0;
            $notification_user_based = !empty($request->notification_user_based) ? $request->notification_user_based : null;
            $schedule_date = !empty($request->schedule_date) ? $request->schedule_date : null;
            $status_id = !empty($request->status_id) ? $request->status_id : null;
            $auth = Auth::user();

            $notificationId = Notification::where('slug', $slug)->update([
                "title" => $title,
                "description" => $description,
                "notification_parameter" => $notification_parameter,
                "notification_user_based" => $notification_user_based,
                "distance_remaining" => $notification_parameter == 2 ?  $distance_remaining : null,
                "days_remaining" => $notification_parameter == 1 ?  $days_remaining : null,
                "penalty_charge" => $penalty_charge,
                "penalty_charge_text" => $penalty_charge_text,
                "is_send_charge" => $is_send_charge == 'on' ? 1 : 0,
                "status_id" => $status_id,
                "schedule_date" => $notification_parameter == 4 ? null : $schedule_date,
                "updated_by" => $auth->user_id,
            ]);

            if ($notificationId) {
                $status = [
                    'status' => Response::HTTP_OK,
                    'url' => route('notifications'),
                    'message' => Lang::get('messages.UPDATE'),
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
    Action    : Delete Notification
    --------------------------------------------------*/
    public function deleteNotification($slug)
    {
        try {
            $deleteResult = $this->notification->deleteNotificationBySlug($slug);
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
