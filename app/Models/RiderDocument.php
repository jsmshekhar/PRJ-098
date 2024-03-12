<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiderDocument extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "rider_documents";
    protected $primaryKey = 'rider_document_id';

    protected $appends = [
        'document_type_name',
    ];

    //1 => Aadhar Card, 2 => Credit Score, 3 => Driving License, 4 => Electicity Bill, 5 => Pan Card, 6 => Passpost, 7 => Voter Id
    public function getDocumentTypeNameAttribute()
    {
        if (is_null($this->document_type) || $this->document_type == "") {
            return "";
        } else {
            switch ($this->document_type) {
                case 1:
                    return 'Aadhar Card';
                    break;
                case 2:
                    return 'Credit Score';
                    break;
                case 3:
                    return 'Driving License';
                    break;
                case 4:
                    return 'Electicity Bill';
                    break;
                case 5:
                    return 'Pan Card';
                    break;
                case 6:
                    return 'Passport';
                    break;
                case 7:
                    return 'Voter Id';
                    break;
                default:
                    return "";
            }
        }
    }
}
