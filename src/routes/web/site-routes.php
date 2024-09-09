<?php

use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\CheckoutController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\ExchangeController;
use App\Http\Controllers\Site\FaqController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PayController;
use App\Http\Controllers\Site\PrivacyController;
use App\Http\Controllers\Site\ShopController;
use App\Http\Controllers\Site\ZipcodeController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('site.home.index');
    Route::get('/crop', 'crop')->name('site.home.crop');
});
Route::controller(ShopController::class)->group(function () {
    Route::get('/shop/{page?}', 'index')->name('site.shop.index');
    Route::get('/detail/{sku?}', 'detail')->name('site.shop.detail');
});
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('site.cart.index');
    Route::get('/cart/data', 'data')->name('site.cart.data');
    Route::post('/cart', 'store')->name('site.cart.store');
    Route::put('/cart', 'update')->name('site.cart.update');
    Route::delete('/cart', 'destroy')->name('site.cart.destroy');
});
Route::controller(CheckoutController::class)->group(function () {
    Route::get('/checkout', 'index')->name('site.checkout.index');
    Route::post('/checkout', 'store')->name('site.checkout.store');
});
Route::name('site.')->group(static function () {
    Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::any('payment/{status}', [PayController::class, 'confirmation'])->name('payment.confirmation');
    Route::get('/privacy', [PrivacyController::class, 'index'])->name('privacy.index');
    Route::get('/exchange', [ExchangeController::class, 'index'])->name('exchange.index');
});
Route::controller(ZipcodeController::class)->group(function () {
    Route::get('/zipcode/{zipcode?}', 'index')->name('site.zipcode.index');
});
