<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vip extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'rank',
        'livecasinorebates',
        'sportsbookrebates',
        'slotsrebate',
        'birthdaybonus',
        'upgradebonus',
        'withdrawalfrequency',
        'withdrawalamount',
        'withdrawalchannels',
        'amount'
    ];
}
