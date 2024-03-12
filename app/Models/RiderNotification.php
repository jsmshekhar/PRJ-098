<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiderNotification extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "rider_notifications";
    protected $primaryKey = 'rider_notification_id';
}
