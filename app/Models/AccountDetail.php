<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'company_id',
        'type',
        'category_type',
        'invoice_number',
        'invoice_date',
        'invoice_type',
        'content',
        'is_tax',
        'price',
        'tax',
        'untax',
        'actual_date',
        'actual_amount',
        'remark',
        'img',
        'share',
        'start_share_date',
        'end_share_date',
        'account_type',
        'created_at',
        'updated_at',
        'subject'
    ];
}
