<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\models\Module;
use Illuminate\Support\Facades\DB;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::truncate();
        $modules = [
            [
                'slug' => "USERMGT",
                'name' => "User Management",
            ],
            [
                'slug' => "CUSTOMERMGT",
                'name' => "Customer Management",
            ],
            [
                'slug' => "HUBMGT",
                'name' => "Hub Management",
            ],
            [
                'slug' => "INVENTRYMGT",
                'name' => "Inventory Management",
            ],
            [
                'slug' => "NOTIFICATIONMGT",
                'name' => "Notification Settings",
            ],
            [
                'slug' => "TRANSACTIONMGT",
                'name' => "Transaction Management",
            ],
            [
                'slug' => "WALLETMGT",
                'name' => "Wallet Management",
            ],
            
            [
                'slug' => "COMPLAINSMGT",
                'name' => "Complains & Queries",
            ],
            [
                'slug' => "REFUNDMGT",
                'name' => "Refund Management",
            ],
            [
                'slug' => "HUBPARTACCESSORIS",
                'name' => "Hub Part Accessories",
            ],
        ]; 
        Module::insert($modules);
    }
}
