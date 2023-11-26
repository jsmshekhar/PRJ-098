<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderBankDetail extends Model
{
    use HasFactory;

    protected $table = "rider_bank_details";
    protected $primaryKey = 'rider_bank_detail_id';
}
