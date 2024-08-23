<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'company_id',
        'created_at',
        'updated_at',
    ];
}
