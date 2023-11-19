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

    public function view_transaction_management()
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
