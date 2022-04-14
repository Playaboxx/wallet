<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('vips', VipController::class);
    $router->resource('user-ranks', RankController::class);
    $router->resource('carousels', CarouselController::class);
    $router->resource('announcements', AnnouncementController::class);
    $router->resource('promotions', PromotionController::class);
    $router->resource('user-banks', UserBankController::class);
    $router->resource('company-banks', CompanyBankController::class);
    $router->resource('deposits', DepositController::class);
    $router->resource('withdraws', WithdrawController::class);
    $router->resource('reasons', ReasonController::class);
    $router->resource('transactions', TransactionController::class);
    $router->resource('system-vars', SystemVarController::class);
    $router->resource('upays', UpayController::class);
});
