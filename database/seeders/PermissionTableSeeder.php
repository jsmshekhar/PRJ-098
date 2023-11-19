<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();
        $permissions = [
            [
                'module_slug' => "USERMGT",
                'module_id' => 1,
                'name' => 'View User',
                "permission_slug" => "view-user",
            ],
            [
                'module_slug' => "USERMGT",
                'module_id' => 1,
                'name' => 'Add User',
                "permission_slug" => "add-user",
            ],
            [
                'module_slug' => "USERMGT",
                'module_id' => 1,
                'name' => 'Edit User',
                "permission_slug" => "edit-user",
            ],
            [
                'module_slug' => "USERMGT",
                'module_id' => 1,
                'name' => 'Delete User',
                "permission_slug" => "delete-user",
            ],
            [
                'module_slug' => "USERMGT",
                'module_id' => 1,
                'name' => 'User Status',
                "permission_slug" => "user-status",
            ],
            [
                'module_slug' => "USERMGT",
                'module_id' => 1,
                'name' => 'View Role',
                "permission_slug" => "view-role",
            ],
            [
                'module_slug' => "USERMGT",
                'module_id' => 1,
                'name' => 'Add Role',
                "permission_slug" => "add-role",
            ],
            [
                'module_slug' => "USERMGT",
                'module_id' => 1,
                'name' => 'Edit Role',
                "permission_slug" => "edit-role",
            ],
            [
                'module_slug' => "USERMGT",
                'module_id' => 1,
                'name' => 'Delete Role',
                "permission_slug" => "delete-role",
            ],
            [
                'module_slug' => "USERMGT",
                'module_id' => 1,
                'name' => 'Allow Permission',
                "permission_slug" => "allow-permission",
            ],
            [
                'module_slug' => "CUSTOMERMGT",
                'module_id' => 2,
                'name' => 'Enable Disable Customer',
                "permission_slug" => "enable-disable-customer",
            ],
            [
                'module_slug' => "HUBMGT",
                'module_id' => 3,
                'name' => 'Hub List',
                "permission_slug" => "hub-list",
            ],
            [
                'module_slug' => "HUBMGT",
                'module_id' => 3,
                'name' => 'Hub View',
                "permission_slug" => "hub-view",
            ],
            [
                'module_slug' => "HUBMGT",
                'module_id' => 3,
                'name' => 'Add Hub',
                "permission_slug" => "add-hub",
            ],
            [
                'module_slug' => "HUBMGT",
                'module_id' => 3,
                'name' => 'Edit Hub',
                "permission_slug" => "edit-hub",
            ],
            [
                'module_slug' => "HUBMGT",
                'module_id' => 3,
                'name' => 'Delete Hub',
                "permission_slug" => "delete-hub",
            ],
            [
                'module_slug' => "HUBMGT",
                'module_id' => 3,
                'name' => 'View EV’s Mapped Hub',
                "permission_slug" => "view-ev-mapped-hub",
            ],
            [
                'module_slug' => "HUBMGT",
                'module_id' => 3,
                'name' => 'Track Refund complaint.',
                "permission_slug" => "track-refund-complaint",
            ],
            [
                'module_slug' => "HUBMGT",
                'module_id' => 3,
                'name' => 'Generate and Send a Refund Status Report',
                "permission_slug" => "generate-send-refund-report",
            ],
            [
                'module_slug' => "INVENTRYMGT",
                'module_id' => 4,
                'name' => 'Add Product Type',
                "permission_slug" => "add-product-type",
            ],
            [
                'module_slug' => "INVENTRYMGT",
                'module_id' => 4,
                'name' => 'Edit Product Type',
                "permission_slug" => "edit-product-type",
            ],
            [
                'module_slug' => "INVENTRYMGT",
                'module_id' => 4,
                'name' => 'Add Inventry',
                "permission_slug" => "add-inventry",
            ],
            [
                'module_slug' => "INVENTRYMGT",
                'module_id' => 4,
                'name' => 'Edit Inventry',
                "permission_slug" => "edit-inventry",
            ],
            [
                'module_slug' => "INVENTRYMGT",
                'module_id' => 4,
                'name' => 'View Inventry',
                "permission_slug" => "view-inventry",
            ],
            [
                'module_slug' => "INVENTRYMGT",
                'module_id' => 4,
                'name' => 'Delete Inventry',
                "permission_slug" => "delete-inventry",
            ],
            [
                'module_slug' => "INVENTRYMGT",
                'module_id' => 4,
                'name' => 'Map inventory to Rider/Customer',
                "permission_slug" => "map-inventry-rider",
            ],
            [
                'module_slug' => "INVENTRYMGT",
                'module_id' => 4,
                'name' => 'Map Inventory to Hub.',
                "permission_slug" => "map-inventry-to-hub",
            ],
            [
                'module_slug' => "NOTIFICATIONMGT",
                'module_id' => 5,
                'name' => 'View Notifications',
                "permission_slug" => "view-notification",
            ],
            [
                'module_slug' => "NOTIFICATIONMGT",
                'module_id' => 5,
                'name' => 'Set Automatic notification',
                "permission_slug" => "set-automatic-notification",
            ],
            [
                'module_slug' => "NOTIFICATIONMGT",
                'module_id' => 5,
                'name' => 'Send Push Notification',
                "permission_slug" => "send-push-notification",
            ],
            [
                'module_slug' => "TRANSACTIONMGT",
                'module_id' => 6,
                'name' => 'View Transaction Details',
                "permission_slug" => "view-transaction-management",
            ],

            [
                'module_slug' => "WALLETMGT",
                'module_id' => 7,
                'name' => 'View wallet Details',
                "permission_slug" => "view-wallet-details",
            ],
            [
                'module_slug' => "WALLETMGT",
                'module_id' => 7,
                'name' => 'Add funds to the wallet',
                "permission_slug" => "add-funds-to-wallet",
            ],
            [
                'module_slug' => "COMPLAINSMGT",
                'module_id' => 8,
                'name' => 'View Complains',
                "permission_slug" => "view-complaint",
            ],
            [
                'module_slug' => "COMPLAINSMGT",
                'module_id' => 8,
                'name' => 'Change complain status',
                "permission_slug" => "change-complaint-status",
            ],
            [
                'module_slug' => "REFUNDMGT",
                'module_id' => 9,
                'name' => 'View refund report',
                "permission_slug" => "view-refund-report",
            ],
            [
                'module_slug' => "REFUNDMGT",
                'module_id' => 9,
                'name' => 'Initiate refund',
                "permission_slug" => "initiate-refund",
            ],
            [
                'module_slug' => "REFUNDMGT",
                'module_id' => 9,
                'name' => 'Update refund status',
                "permission_slug" => "update-refund-status",
            ],
        ];
        Permission::insert($permissions);
    }
}
