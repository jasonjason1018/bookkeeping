<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrcodeSerial extends Model
{
    use HasFactory;
    protected $table = "qrcode_serials";

    public $timestamps = false;
    protected $fillable = [
        'id',
        'is_download',
        'serial',
        'url',
    ];
}
