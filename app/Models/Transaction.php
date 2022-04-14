<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'ref_no', 'user_id', 'transaction_type', 'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deposit()
    {
        return $this->belongsTo(Deposit::class, 'ref_no');
    }

    public function withdraw()
    {
        return $this->belongsTo(Withdraw::class, 'ref_no');
    }
}
