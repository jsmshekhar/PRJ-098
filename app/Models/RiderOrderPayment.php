<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiderOrderPayment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "rider_order_payments";
    protected $primaryKey = 'rider_order_payment_id';
}
