<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'title', 'content', 'user_id', 'viewed_at',
    ];
}
