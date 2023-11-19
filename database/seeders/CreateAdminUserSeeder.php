<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'slug' => slug(),
            'name' => 'Adminstrator',
            'email' => 'admin@admin.com',
            'email_verified_at' => NOW(),
            'password' => bcrypt('admin123')
        ]);
    }
}
