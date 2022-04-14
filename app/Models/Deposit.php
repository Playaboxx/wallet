<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'ref_no', 'user_id', 'bank_id', 'approved_by_id', 'amount', 'status', 'proof', 'reason_id', 'others'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyBank::class, 'bank_id');
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
