<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaFile extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "media_files";
    protected $primaryKey = 'media_file_id';
}
