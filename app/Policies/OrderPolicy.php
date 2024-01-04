<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
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

    public function view_cviewomplaint()
    {
        $permission = User::getPermissions();
        if (in_array('view', $permission)) {
            return true;
        } else {
            return false;
        }
    }
    public function assign_ev()
    {
        $permission = User::getPermissions();
        if (in_array('assign-ev', $permission)) {
            return true;
        } else {
            return false;
        }
    }
}
