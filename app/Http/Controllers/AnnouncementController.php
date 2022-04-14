<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{

    public function marquee()
    {
        return response()->json([
            'announcement' => Announcement::where('status', 1)->latest()->take(10)->get(),
        ]);
    }


    public function index()
    {
        return response()->json([
            'announcement' => Announcement::where('status', 1)->latest()->get(),
        ]);
    }

    public function show($id)
    {
        $announcement = Announcement::find($id);

        if (!$announcement) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, announcement content not found.'
            ], 400);
        }

        return $announcement;
    }
}
