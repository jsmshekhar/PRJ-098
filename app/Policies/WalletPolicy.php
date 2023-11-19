<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WalletPolicy
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

    public function view_wallet_details()
    {
        $permission = User::getPermissions();
        if (in_array('view-wallet-details', $permission)) {
            return true;
        } else {
            return false;
        }
    }

    public function add_funds_to_wallet()
    {
        $permission = User::getPermissions();
        if (in_array('add-funds-to-wallet', $permission)) {
            return true;
        } else {
            return false;
        }
    }
}
