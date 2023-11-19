<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view_notification()
    {
        $permission = User::getPermissions();
        if(in_array('view-notification', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function set_automatic_notification()
    {
        $permission = User::getPermissions();
        if(in_array('set-automatic-notification', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function send_push_notification()
    {
        $permission = User::getPermissions();
        if(in_array('send-push-notification', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }
}
