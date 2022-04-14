<?php

namespace App\Http\Controllers;

use App\Models\CompanyBank;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CompanyBankController extends Controller
{
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return response()->json(['bank' => CompanyBank::where('status', 1)->get()]);
    }
}
