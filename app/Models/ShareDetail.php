<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareDetail extends Model
{
    use HasFactory;
    protected $table = 'shareDetails';
    protected $fillable = [
        'id',
        'account_id',
        'date',
        'amount',
    ];
    public $timestamps = false;
}
