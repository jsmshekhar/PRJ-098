<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
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

    public function enable_disable_customer()
    {
        $permission = User::getPermissions();
        if(in_array('enable-disable-customer', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }
}
