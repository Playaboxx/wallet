<?php

namespace App\Http\Controllers;

use App\Models\UserBank;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class UserBankController extends Controller
{
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return response()->json(['bank' => $this->user->UserBank()->where('status', 1)->get()]);
    }

    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('bank_name', 'bank_acc', 'holder_name');
        $validator = Validator::make($data, [
            'bank_name' => 'required|string',
            'bank_acc' => 'required|digits_between:9,17',
            'holder_name' => 'required|string'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new UserBank
        $UserBank = $this->user->UserBank()->create([
            'user_id' => $this->user->id,
            'bank_name' => $request->bank_name,
            'bank_acc' => $request->bank_acc,
            'holder_name' => $request->holder_name,
        ]);

        //Product created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User bank created successfully',
            'data' => $UserBank
        ], Response::HTTP_CREATED);
    }


    public function update(Request $request, UserBank $UserBank)
    {
        //Validate data
        $data = $request->only('status');
        $validator = Validator::make($data, [
            'status' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update user bank
        $UserBank = $UserBank->update([
            'status' => $request->status
        ]);

        //Product updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'User Bank updated successfully',
            'data' => $UserBank
        ], Response::HTTP_CREATED);
    }

    public function destroy(UserBank $UserBank)
    {
        $UserBank->delete();

        return response()->json([
            'success' => true,
            'message' => 'User bank deleted successfully'
        ], Response::HTTP_CREATED);
    }
}
