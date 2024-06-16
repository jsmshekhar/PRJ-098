<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionCollectedAmmount extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "transaction_collected_ammounts";
    protected $primaryKey = 'transaction_collected_id';
}
