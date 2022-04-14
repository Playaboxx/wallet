<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\carbon;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return response()->json(['notification' => Notification::all()]);
    }

    public function count()
    {
        return response()->json(['count' => Notification::where('user_id', $this->user->id)->where('viewed_at', null)->count()]);
    }

    public function show($id)
    {
        $notification = Notification::find($id);

        $notification->update([
            'viewed_at' => Carbon::now()
        ]);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, notification record not found.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Updated!.'
        ], 200);
    }
}
