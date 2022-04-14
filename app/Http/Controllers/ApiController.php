<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\UserRank;
use App\Models\Vip;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{

    public function index(Request $request)
    {
        try {
            if (!$this->user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'user_not_found'], 404);
            }
            if ($this->user->status === 0) {
                $this->invalidToken();
                return response()->json(['error' => 'Your account has been suspended, kindly contact admin for more information.'], 401);
            }

            $this->user = JWTAuth::parseToken()->authenticate();
            $email = $this->user->email;
            $em   = explode("@", $email);
            $emailName = implode(array_slice($em, 0, count($em) - 1));
            $emailLen  = floor(strlen($emailName) / 2);
            $emailLength = strlen($emailName);
            $emailHiddenCount = $emailLength - $emailLen;
            $email = substr($emailName, 0, $emailLen) . str_repeat('*', $emailHiddenCount) . "@" . end($em);

            $phoneNumber = $this->user->phone;
            $phoneNumberLength = strlen($phoneNumber);
            $phoneNumberVisibleCount = (int) round($phoneNumberLength / 4) + 1;
            $phoneNumberHiddenCount = $phoneNumberLength - ($phoneNumberVisibleCount * 2);
            $phoneNumber = str_repeat('*', $phoneNumberVisibleCount) . str_repeat('*', $phoneNumberHiddenCount) . substr($phoneNumber, ($phoneNumberVisibleCount * -1), $phoneNumberVisibleCount);

            $username = $this->user->username;
            $fullname = $this->user->name;
            $nickname = $this->user->nickname;
            $birth = $this->user->birth;

            $collection = collect($this->user);
            $profile = $collection->except(['email', 'phone', 'username', 'nickname', 'name', 'birth']);
            $profile->put('email', $email);
            $profile->put('phone', $phoneNumber);
            $profile->put('username', $username);
            $profile->put('nickname', $nickname);
            $profile->put('name', $fullname);
            $profile->put('birth', $birth);

            return response()->json(compact('profile'));
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

    public function register(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirmation', 'phone', 'nickname', 'username', 'birth');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|max:50|confirmed',
            'phone' => 'required|numeric|unique:users',
            'nickname' => 'required|string|unique:users',
            'username' => 'required|string|unique:users',
            'birth' => 'required|date|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'nickname' => $request->nickname,
            'username' => $request->username,
            'birth' => $request->birth,
        ]);

        UserRank::create([
            'user_id' => $user->id,
            'vip_id' => 1
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'token' => $token,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_CREATED);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:8|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        //Request is validated
        //Crean token
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email or password incorrect',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }

        try {
            $user = Auth::user();
            if ($user->status === 0) {
                $this->invalidToken();
                return response()->json(['error' => 'Your account has been suspended, kindly contact admin for more information.'], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been suspended, kindly contact admin for more information.',
            ], 500);
        }

        //Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePassword(Request $request)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $validator = Validator::make($request->only('current_password', 'new_password', 'new_password_confirmation'), [
            'current_password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        } else {
            if (Hash::check($request->current_password, $this->user->password)) {
                if ($request->current_password === $request->new_password) {
                    return response()->json(['error' => 'Your password was no change.'], 400);
                }
                $this->user->password = Hash::make($request->new_password);
                $this->user->save();
                return response()->json(['status' => 'success', 'message' => 'You have successfully update your password.'], 200);
            } else {
                return response()->json(['error' => 'Your current password is incorrect.'], 400);
            }
        }
    }

    public function check_version()
    {
        $app_version = DB::select('select app_version,ios from system_vars');
        return response()->json(['app_version' => $app_version]);
    }

    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }

    public static function refreshToken()
    {
        $current_token = JWTAuth::getToken();
        $token = JWTAuth::refresh($current_token);

        return $token;
    }

    public static function invalidToken()
    {
        $current_token = JWTAuth::getToken();
        JWTAuth::setToken($current_token)->invalidate();
    }
}
