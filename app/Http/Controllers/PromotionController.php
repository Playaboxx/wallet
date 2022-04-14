<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        return response()->json([
            'all' => Promotion::where('status', 1)->get(),
            'esport' => Promotion::where('status', 1)->where('type', 1)->latest()->get(),
        ]);
    }

    public function show($id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, promotion content not found.'
            ], 400);
        }

        return $promotion;
    }
}
