<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $userDeposit = $this->user->deposit()->orderBy('id', 'DESC')->with('reason')->get();
        $userWithdraw = $this->user->withdraw()->orderBy('id', 'DESC')->with('reason')->get();
        $userTransaction = $this->user->transaction()->orderBy('id', 'DESC')->get();
        $record = collect();
        $record->put('user_deposit', $userDeposit);
        $record->put('user_withdraw', $userWithdraw);
        $record->put('user_transaction', $userTransaction);
        return response()->json(compact('record'));
    }
}
