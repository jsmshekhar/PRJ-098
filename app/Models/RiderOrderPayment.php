<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderOrderPayment extends Model
{
    use HasFactory;
    protected $table = "rider_order_payments";
    protected $primaryKey = 'rider_order_payment_id';
}
