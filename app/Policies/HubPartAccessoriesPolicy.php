<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HubPartAccessoriesPolicy
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

    public function raise_request()
    {
        $permission = User::getPermissions();
        if (in_array('raise-request', $permission)) {
            return true;
        } else {
            return false;
        }
    }

    public function assiegn_request()
    {
        $permission = User::getPermissions();
        if (in_array('assiegn-request', $permission)) {
            return true;
        } else {
            return false;
        }
    }
}
