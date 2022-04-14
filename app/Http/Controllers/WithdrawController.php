<?php

namespace App\Http\Controllers;

use App\Models\Withdraw;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        try {
            if (!$this->user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'user_not_found'], 404);
            }
            if ($this->user->status === 0) {
                $this->invalidToken();
                return response()->json(['error' => 'Your account has been suspended, kindly contact admin for more information.'], 401);
            }

            return response()->json(['withdraw' => $this->user->withdraw()
                ->join('user_banks', 'user_bank_id', '=', 'user_banks.id')
                ->get()]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $token = $this->refreshToken();
            return response()->json(compact('token'));
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
            return response()->json(['error' => 'token_blacklisted'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'token_absent'], 401);
        }
    }

    public function store(Request $request)
    {
        $userbankID = $request->user_bank_id;
        if ($this->user->balance < $request->amount) {
            return response()->json(['error' => "Insufficient balance"], 200);
        }
        // $lastwithdraw = $this->user->withdraw()->latest()->first();
        // $betcount = 0;
        // if (!$lastwithdraw == null) {
        //     $lastwithdrawdate = $lastwithdraw->created_at->format('Y-m-d');
        //     $getbet = $this->user->betRecord()->where('status', 1)->whereDate('created_at', '>', $lastwithdrawdate)
        //         ->groupBy('date_id')->get(); //->distinct()->count('date_id');
        //     foreach ($getbet as $bet) {
        //         $searchresult = LotteryResult::where('date_id', $bet->date_id)->count();
        //         if ($searchresult > 0) {
        //             $betcount += 1;
        //         }
        //     }
        // } else {
        //     $getbet = $this->user->betRecord()->where('status', 1)->groupBy('date_id')->get();
        //     foreach ($getbet as $bet) {
        //         $searchresult = LotteryResult::where('date_id', $bet->date_id)->count();
        //         if ($searchresult > 0) {
        //             $betcount += 1;
        //         }
        //     }
        // }

        // if ($betcount < 1) {
        //     return response()->json(
        //         ['error' => 'You must proceed at least 2 bet with different date before withdraw. (Count reset per withdraw)'],
        //         200
        //     );
        // }
        // $checkpending = $this->user->withdraw()->where('status', 0)->count();
        // if ($checkpending > 0) {
        //     return response()->json(
        //         ['error' => 'You can only process 1 withdraw request at the same time.'],
        //         200
        //     );
        // }

        //Validate data
        if ($request->has(['bank_name', 'bank_acc', 'holder'])) {
            $data = $request->only('bank_name', 'bank_acc', 'holder', 'amount');
            $validator = Validator::make($data, [
                'bank_name' => 'required|string',
                'bank_acc' => 'required|numeric',
                'holder' => 'required|string',
                'amount' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
            }
            $bank = $this->user->UserBank()->create([
                'user_id' => $this->user->id,
                'bank_name' => $request->bank_name,
                'bank_acc' => $request->bank_acc,
                'holder_name' => $request->holder,
            ]);
            $userbankID = $bank->id;
        } else {
            $data = $request->only('amount', 'user_bank_id');
            $validator = Validator::make($data, [
                'amount' => 'required|numeric',
                'user_bank_id' => 'required|numeric',
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
            }
        }
        //Request is valid, create new user_bank

        $withdraw = $this->user->withdraw()->create([
            'user_id' => $this->user->id,
            'amount' => $request->amount,
            'user_bank_id' => $userbankID,
            'ref_no' =>  'W' . time() . '-' . $this->user->id
        ]);

        $user = User::where('id', $this->user->id)->first();
        if ($user->balance >= $request->amount) {
            $user->balance -= $request->amount;
            $user->save();
        } else {
            return $this->response()->error('Insufficient balance');
        }

        return response()->json([
            'success' => true,
            'message' => 'Withdraw request sent successfully',
            'data' => $user->balance
        ], Response::HTTP_CREATED);
    }
}
