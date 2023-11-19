<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComplaintPolicy
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

    public function view_complaint()
    {
        $permission = User::getPermissions();
        if (in_array('view-complaint', $permission)) {
            return true;
        } else {
            return false;
        }
    }

    public function change_complaint_status()
    {
        $permission = User::getPermissions();
        if (in_array('change-complaint-status', $permission)) {
            return true;
        } else {
            return false;
        }
    }
}
