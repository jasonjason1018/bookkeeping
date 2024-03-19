<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    use HasFactory;
    protected $table = "accountDetails";

    protected $fillable = [
        'id',
        'type',
        'invoice_date',
        'invoice_type',
        'content',
        'price',
        'tax',
        'untax',
        'actual_date',
        'remark',
        'img',
        'share',
        'start_share_date',
        'end_share_date',
        'account_type'
    ];
    public $timestamps = false;
}
