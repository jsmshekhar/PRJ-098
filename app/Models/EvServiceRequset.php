<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvServiceRequset extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "ev_service_requsets";
    protected $primaryKey = 'requset_id';
}
