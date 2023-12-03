<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CreateRiderSeeder;
use Database\Seeders\ModuleTableSeeder;
use Database\Seeders\CreateAdminUserSeeder;
use Database\Seeders\PermissionTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();

        $this->call([
            CreateRiderSeeder::class,
            CreateAdminUserSeeder::class,
            ModuleTableSeeder::class,
            PermissionTableSeeder::class,
        ]);
    }
}
