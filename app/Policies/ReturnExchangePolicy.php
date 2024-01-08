<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReturnExchangePolicy
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

    public function view()
    {
        $permission = User::getPermissions();
        if (in_array('view', $permission)) {
            return true;
        } else {
            return false;
        }
    }
    public function return()
    {
        $permission = User::getPermissions();
        if (in_array('return', $permission)) {
            return true;
        } else {
            return false;
        }
    }
    public function exchange()
    {
        $permission = User::getPermissions();
        if (in_array('exchange', $permission)) {
            return true;
        } else {
            return false;
        }
    }
}
