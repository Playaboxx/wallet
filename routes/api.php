<?php

use App\Http\Controllers\AnnouncementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CompanyBankController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\UpayController;
use App\Http\Controllers\UserBankController;
use App\Http\Controllers\VipController;
use App\Http\Controllers\WithdrawController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);
//check version
Route::get('check_version', [ApiController::class, 'check_version']);
//update password
Route::post('update/password', [ApiController::class, 'updatePassword']);
//Vip
Route::get('vip', [VipController::class, 'index']);
//Carousel
Route::get('carousel', [CarouselController::class, 'index']);
//announcement
Route::get('announcement', [AnnouncementController::class, 'index']);
Route::get('marquee', [AnnouncementController::class, 'marquee']);
Route::get('announcement/{id}', [AnnouncementController::class, 'show']);
//promotion
Route::get('promotion', [PromotionController::class, 'index']);
Route::get('promotion/{id}', [PromotionController::class, 'show']);

//upay
Route::post('linkback', [UpayController::class, 'index']);
Route::post('callback', [UpayController::class, 'store']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', [ApiController::class, 'logout']);
    //profile
    Route::get('profile', [ApiController::class, 'index']);
    //notification
    Route::get('notification', [NotificationController::class, 'index']);
    Route::get('count', [NotificationController::class, 'count']);
    Route::put('notification/{id}', [NotificationController::class, 'show']);
    //user bank
    Route::get('user_bank', [UserBankController::class, 'index']);
    Route::post('user_bank/create', [UserBankController::class, 'store']);
    Route::put('user_bank/update/{user_bank}',  [UserBankController::class, 'update']);
    Route::delete('user_bank/delete/{user_bank}',  [UserBankController::class, 'destroy']);
    //company bank
    Route::get('company-bank', [CompanyBankController::class, 'index']);
    //withdraw
    Route::get('withdraw', [WithdrawController::class, 'index']);
    Route::post('withdraw/request', [WithdrawController::class, 'store']);
    //deposit
    Route::get('deposit', [DepositController::class, 'index']);
    Route::post('deposit/request', [DepositController::class, 'store']);
    //Transaction
    Route::get('record', [TransactionControlle::class, 'index']);
});
