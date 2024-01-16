<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
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
        if(in_array('view-transaction-management', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }
}
