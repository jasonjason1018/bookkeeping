<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    use HasFactory;
    protected $table = "ledgerEntry";
    protected $fillable = [
        'item',
        'update_at',
        'create_at'
    ];
    public $timestamps = false;
}
