<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HubPolicy
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
    public function hub_view()
    {
        $permission = User::getPermissions();
        if (in_array('hub-view', $permission)) {
            return true;
        } else {
            return false;
        }
    }
    public function hub_list()
    {
        $permission = User::getPermissions();
        if (in_array('hub-list', $permission)) {
            return true;
        } else {
            return false;
        }
    }
    public function add_hub()
    {
        $permission = User::getPermissions();
        if(in_array('add-hub', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function edit_hub()
    {
        $permission = User::getPermissions();
        if(in_array('edit-hub', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function delete_hub()
    {
        $permission = User::getPermissions();
        if(in_array('delete-hub', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function view_ev_mapped_hub()
    {
        $permission = User::getPermissions();
        if(in_array('view-ev-mapped-hub', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function track_refund_complaint()
    {
        $permission = User::getPermissions();
        if (in_array('track-refund-complaint', $permission)) {
            return true;
        } else {
            return false;
        }
    }

    public function generate_send_refund_report()
    {
        $permission = User::getPermissions();
        if (in_array('generate-send-refund-report', $permission)) {
            return true;
        } else {
            return false;
        }
    }
}
