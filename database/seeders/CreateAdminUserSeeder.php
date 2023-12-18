<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        $slug = slug();
        $user = User::create([
            'slug' => $slug,
            'first_name' => 'Adminstrator',
            'last_name' => '',
            'phone' => '9935270134',
            'email' => 'admin@admin.com',
            'email_verified_at' => NOW(),
            'password' => bcrypt('admin123'),
            'user_slug' => $slug
        ]);
    }
}
