<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'ref_no', 'user_id', 'user_bank_id', 'amount', 'status', 'approved_by_id', 'reason_id', 'others'
    ];

    public function bank()
    {
        return $this->belongsTo(UserBank::class, 'user_bank_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reason()
    {
        return $this->belongsTo(Reason::class, 'reason_id');
    }

    public function admin()
    {
        return $this->belongsTo(Administrator::class, 'processed_by_id');
    }
}
