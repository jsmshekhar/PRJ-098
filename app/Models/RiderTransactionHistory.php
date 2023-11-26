<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderTransactionHistory extends Model
{
    use HasFactory;
    protected $table = "rider_transaction_histories";
    protected $primaryKey = 'rider_transaction_id';

    protected $appends = [
        'transaction_type_name', 'transaction_mode_name', 'payment_status'
    ];

    //1 => Credited, 2 => Debited
    public function getTransactionTypeNameAttribute()
    {
        if (is_null($this->transaction_type) || $this->transaction_type == "") {
            return "";
        } else {
            switch ($this->transaction_type) {
                case 1:
                    return 'Credited';
                    break;
                case 2:
                    return 'Debited';
                    break;
                default:
                    return "";
            }
        }
    }

    //1 => Card, 2 => Wallet, 3 => UPI
    public function getTransactionModeNameAttribute()
    {
        if (is_null($this->transaction_mode) || $this->transaction_mode == "") {
            return "";
        } else {
            switch ($this->transaction_mode) {
                case 1:
                    return 'Card';
                    break;
                case 2:
                    return 'Wallet';
                    break;
                case 3:
                    return 'UPI';
                    break;
                default:
                    return "";
            }
        }
    }

    //1 => Succes, 2 => Pending, 3 => Failed, 4 => Rejected
    public function getPaymentStatusAttribute()
    {
        if (is_null($this->status_id) || $this->status_id == "") {
            return "";
        } else {
            switch ($this->status_id) {
                case 1:
                    return 'Succes';
                    break;
                case 2:
                    return 'Pending';
                    break;
                case 3:
                    return 'Failed';
                    break;
                case 4:
                    return 'Rejected';
                    break;
                default:
                    return "";
            }
        }
    }
}
