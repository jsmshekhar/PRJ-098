<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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

    public function role_view()
    {
        $permission = User::getPermissions();
        if(in_array('role-view', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function role_create()
    {
        $permission = User::getPermissions();
        if(in_array('role-create', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function role_edit()
    {
        $permission = User::getPermissions();
        if(in_array('role-edit', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }
}
