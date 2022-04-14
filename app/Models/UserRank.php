<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRank extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'user_id',
        'vip_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vip()
    {
        return $this->belongsTo(Vip::class, 'vip_id');
    }
}
