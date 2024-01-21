<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderToken extends Model
{
    use HasFactory;
    protected $table = "rider_tokens";
    protected $primaryKey = 'rider_token_id';
}
