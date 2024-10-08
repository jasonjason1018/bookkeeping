<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'item',
        'create_at',
        'update_at'
    ];

    public $timestamps = false;

}
