<?php

namespace App\Http\Controllers;

use App\Models\CompanyBank;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return response()->json(['deposit' => $this->user->deposit()->latest()->get()]);
    }

    public function store(Request $request)
    {
        $bank = CompanyBank::findOrFail($request->bank_id);
        if ($bank->status != 1) {
            return response()->json(['error' => 'Selected bank is unavailable now!'], 400);
        }
        $checkpending = $this->user->deposit()->where('status', 0)->count();
        if ($checkpending > 0) {
            return response()->json(
                ['error' => 'You can only process 1 deposit request at the same time.'],
                200
            );
        }
        $data = $request->only('bank_id', 'amount', 'proof');
        $validator = Validator::make($data, [
            'bank_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'proof' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        if ($request->hasFile('proof')) {
            $file      = $request->file('proof');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = date('YmdHis') . '_' . $filename;
            $move = $file->move(public_path('deposit'), $picture);

            if ($move) {
                $deposit = $this->user->deposit()->create([
                    'user_id' => $this->user->id,
                    'bank_id' => $request->bank_id,
                    'amount' => $request->amount,
                    'proof' => $picture,
                    'ref_no' =>  'D' . time() . '-' . $this->user->id
                ]);

                // $topic = 'We have received your deposit request';
                // $content = 'We have received your request and will process it within 1-3 minutes.';

                return response()->json([
                    'success' => true,
                    'message' => 'Deposit record created successfully',
                    'data' => $deposit
                ], Response::HTTP_CREATED);
            } else {
                return response()->json(['error' => 'Something went wrong! Please try again later.'], 400);
            }
        } else {
            return response()->json(['error' => 'Please select image first!'], 400);
        }
    }
}
