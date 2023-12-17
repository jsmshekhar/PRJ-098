<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HubPartAccessories extends Model
{
    use HasFactory;
    protected $table = "hub_part_accessories";
    protected $primaryKey = 'hub_part_accessories_id';
}
