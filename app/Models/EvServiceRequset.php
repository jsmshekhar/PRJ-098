<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvServiceRequset extends Model
{
    use HasFactory;
    protected $table = "ev_service_requsets";
    protected $primaryKey = 'requset_id';
}
