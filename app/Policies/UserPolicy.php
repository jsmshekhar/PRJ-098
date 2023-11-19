<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
    public function view_user()
    {
        $permission = User::getPermissions();
        if (in_array('view-user', $permission)) {
            return true;
        } else {
            return false;
        }
    }
    public function add_user()
    {
        $permission = User::getPermissions();
        if(in_array('add-user', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function edit_user()
    {
        $permission = User::getPermissions();
        if(in_array('edit-user', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function delete_user()
    {
        $permission = User::getPermissions();
        if(in_array('delete-user', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }
    public function user_status()
    {
        $permission = User::getPermissions();
        if(in_array('user-status', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function view_role()
    {
        $permission = User::getPermissions();
        if (in_array('view-role', $permission)) {
            return true;
        } else {
            return false;
        }
    }
    public function add_role()
    {
        $permission = User::getPermissions();
        if (in_array('add-role', $permission)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit_role()
    {
        $permission = User::getPermissions();
        if (in_array('edit-role', $permission)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_role()
    {
        $permission = User::getPermissions();
        if (in_array('delete-role', $permission)) {
            return true;
        } else {
            return false;
        }
    }
    public function allow_permission()
    {
        $permission = User::getPermissions();
        if (in_array('allow-permission', $permission)) {
            return true;
        } else {
            return false;
        }
    }
}
