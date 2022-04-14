<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'content', 'image', 'status', 'title', 'type'
    ];
}
