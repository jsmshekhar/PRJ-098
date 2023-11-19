<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefundPolicy
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

    public function view_refund_report()
    {
        $permission = User::getPermissions();
        if (in_array('view-refund-report', $permission)) {
            return true;
        } else {
            return false;
        }
    }

    public function initiate_refund()
    {
        $permission = User::getPermissions();
        if (in_array('initiate-refund', $permission)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_refund_status()
    {
        $permission = User::getPermissions();
        if (in_array('update-refund-status', $permission)) {
            return true;
        } else {
            return false;
        }
    }
}
