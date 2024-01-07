<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnExchange extends Model
{
    use HasFactory;
    protected $table = "return_exchanges";
    protected $primaryKey = 'return_exchange_id';
}
