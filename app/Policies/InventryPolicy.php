<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventryPolicy
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

    public function add_inventry()
    {
        $permission = User::getPermissions();
        if(in_array('add-inventry', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function edit_inventry()
    {
        $permission = User::getPermissions();
        if(in_array('edit-inventry', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function view_inventry()
    {
        $permission = User::getPermissions();
        if(in_array('view-inventry', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function delete_inventry()
    {
        $permission = User::getPermissions();
        if(in_array('delete-inventry', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function map_inventry_rider()
    {
        $permission = User::getPermissions();
        if (in_array('map-inventry-rider', $permission)) {
            return true;
        } else {
            return false;
        }
    }

    public function map_inventry_to_hub()
    {
        $permission = User::getPermissions();
        if (in_array('map-inventry-to-hub', $permission)) {
            return true;
        } else {
            return false;
        }
    }

    public function add_product_type()
    {
        $permission = User::getPermissions();
        if (in_array('add-product-type', $permission)) {
            return true;
        } else {
            return false;
        }
    }
    public function edit_product_type()
    {
        $permission = User::getPermissions();
        if (in_array('edit-product-type', $permission)) {
            return true;
        } else {
            return false;
        }
    }
}
