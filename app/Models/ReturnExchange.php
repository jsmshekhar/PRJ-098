<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnExchange extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "return_exchanges";
    protected $primaryKey = 'return_exchange_id';

    protected $appends = [
        'status_display', 'request_for_name',
    ];

    public function getStatusDisplayAttribute()
    {
        if (is_null($this->status_id) || $this->status_id == "") {
            return "";
        } else {
            switch ($this->status_id) {
                case 1:
                    return 'Resolved';
                    break;
                case 2:
                    return 'Pending';
                    break;
                default:
                    return "";
            }
        }
    }

    public function getRequestForNameAttribute()
    {
        if (is_null($this->request_for) || $this->request_for == "") {
            return "";
        } else {
            switch ($this->request_for) {
                case 1:
                    return 'Return';
                    break;
                case 2:
                    return 'Exchange';
                    break;
                default:
                    return "";
            }
        }
    }

    public function rider()
    {
        return $this->hasOne(Rider::class, 'rider_id', 'rider_id')->whereNull('deleted_at');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'product_id', 'mapped_vehicle_id')->whereNull('deleted_at');
    }

    public function hub()
    {
        return $this->hasOne(Hub::class, 'hub_id', 'hub_id')->whereNull('deleted_at');
    }

    public function order()
    {
        return $this->hasOne(RiderOrder::class, 'order_id', 'order_id')->whereNull('deleted_at');
    }
}
