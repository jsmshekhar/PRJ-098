<?php

namespace Database\Seeders;

use App\Models\Rider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('site_configuration')->truncate();
        $company = [
            [
                'slug' => slug(),
                'company_name' => "Evatoz Solutions",
                'company_address' => "Noida Sector 22",
                'status_id' => 1,
            ],
        ];
        Rider::insert($company);
    }
}
