<?php

namespace App\Models;

use App\Models\Rider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiderOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "rider_orders";
    protected $primaryKey = 'order_id';



    public function rider()
    {
        return $this->hasOne(Rider::class, 'rider_id', 'rider_id')->whereNull('deleted_at');
    }
}
