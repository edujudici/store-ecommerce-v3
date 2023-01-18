<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Shopper\FavoriteController;
use App\Http\Controllers\Shopper\NotificationController;
use App\Http\Controllers\Shopper\ShopperController;

Route::prefix('shopper')->group(function () {
    Auth::routes();
});

Route::prefix('shopper')->controller(LoginController::class)->group(function () {
    Route::get('/login/google', 'redirectToGoogle')->name('shopper.google.login');
    Route::get('/login/google/callback', 'handleGoogleCallback');
});

Route::middleware('shopper')->prefix('shopper')->controller(ShopperController::class)->group(function () {
    Route::get('/', 'index')->name('shopper.index');
    Route::get('/data', 'dataIndex')->name('shopper.data.index');
    Route::post('/data', 'dataUpdate')->name('shopper.data.update');
    Route::post('/password', 'passwordUpdate')->name('shopper.password.update');
    Route::get('/orders/{protocol?}', 'ordersIndex')->name('shopper.orders.index');
    Route::get('/vouchers', 'vouchersIndex')->name('shopper.vouchers.index');
});
Route::middleware('shopper')->prefix('shopper')->controller(FavoriteController::class)->group(function () {
    Route::get('/favorites', 'index')->name('shopper.favorites.index');
    Route::post('/favorite', 'store')->name('shopper.favorite.store');
    Route::delete('/favorite-delete', 'destroy')->name('shopper.favorite.destroy');
});
Route::middleware('shopper')->prefix('shopper')->controller(NotificationController::class)->group(function () {
    Route::get('/notifications', 'index')->name('shopper.notifications.index');
    Route::post('/notifications/read', 'read')->name('shopper.notifications.read');
});
