<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Models\User;

trait FireBaseNotification
{

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : Automatic Notification
    --------------------------------------------------*/
    public function sendAutomaticNotification($deviceToken, $title, $body = "", $url = "")
    {
        $headers = [
            'Authorization: key=' . env('FIREBASE_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        $notificationData = [
            'body' => $body,
            'title' => $title,
            'click_action' => $url,
        ];

        $payload = [
            'to' => $deviceToken,
            'data' => $notificationData,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $result = curl_exec($ch);
        curl_close($ch);
    }
}
