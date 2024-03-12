<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiderBankDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "rider_bank_details";
    protected $primaryKey = 'rider_bank_detail_id';
}
