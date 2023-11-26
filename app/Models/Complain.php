<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    use HasFactory;

    protected $table = "complains";
    protected $primaryKey = 'complain_id';

    protected $appends = [
        'display_status'
    ];

    //1 => Resolved, 2 => Pending, 3 => Discard
    public function getDisplayStatusAttribute()
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
                case 3:
                    return 'Discard';
                    break;
                default:
                    return "";
            }
        }
    }

    public function category()
    {
        return $this->hasOne(ComplainCategory::class, 'slug', 'complain_category')->whereNull('deleted_at');
    }
}
