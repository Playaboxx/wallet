<?php

namespace App\Http\Controllers;

use App\Models\UserRank;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRankController extends Controller
{
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return response()->json(['rank' => $this->user->UserRank()->get()]);
    }
}
