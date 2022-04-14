<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // $this->reportable(function (Throwable $e) {
        //     //
        // });

        $this->renderable(function (TokenInvalidException $e, $request) {
            return Response::json(['error' => 'Invalid token'], 401);
        });
        $this->renderable(function (TokenExpiredException $e, $request) {
            $token = $this->refreshToken();
            return Response::json(['error' => 'Token has Expired', 'token' => $token], 401);
        });

        $this->renderable(function (JWTException $e, $request) {
            return Response::json(['error' => 'Token not parsed'], 401);
        });

        $this->renderable(function (TokenBlacklistedException $e, $request) {
            return Response::json(['error' => 'Token is blacklisted'], 401);
        });
    }

    public static function refreshToken()
    {
        $current_token = JWTAuth::getToken();
        $token = JWTAuth::refresh($current_token);

        return $token;
    }
}
