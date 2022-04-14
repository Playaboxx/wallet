<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBank extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'bank_name', 'bank_acc', 'holder_name', 'status', 'icon'
    ];
}
