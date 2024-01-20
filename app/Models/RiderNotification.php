<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderNotification extends Model
{
    use HasFactory;
    protected $table = "rider_notifications";
    protected $primaryKey = 'rider_notification_id';
}
