<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\DefaultDatetimeFormat;

class Upay extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'merchantId',
        'apiKey',
        'referenceId',
        'itemDesc',
        'status',
        'statusDesc',
        'declineReason',
        'transactionId',
        'transactionDesc',
        'amount',
        'transactionAmount',
        'currency',
        'designatedBank',
        'designatedAccountNo',
        'designatedAccountName',
        'srcAccountNo',
        'bankRef',
        'requestDate',
        'transactionDate',
    ];
}
