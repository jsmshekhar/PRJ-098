<?php

namespace Database\Seeders;

use App\Models\Rider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateRiderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('riders')->truncate();
        $riders = [
            [
                'slug' => slug(),
                'name' => "Jhon Rider",
                'email' => "jhon@gmail.com",
                'password' => bcrypt("admin@123"),
                'phone' => "9935270134",
                'email_verified_at' => NOW(),
                'activated_at' => NOW(),
                'created_at' => NOW(),
                'status_id' => 1,
            ],
        ];
        Rider::insert($riders);
    }
}
