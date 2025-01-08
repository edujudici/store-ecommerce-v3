<?php

use App\Http\Controllers\Painel\AdminLoginController;
use App\Http\Controllers\Painel\AdminRegisterController;
use App\Http\Controllers\Painel\BannerController;
use App\Http\Controllers\Painel\BrandController;
use App\Http\Controllers\Painel\CategoryController;
use App\Http\Controllers\Painel\CompanyController;
use App\Http\Controllers\Painel\ContactController;
use App\Http\Controllers\Painel\DashboardController;
use App\Http\Controllers\Painel\FaqController;
use App\Http\Controllers\Painel\FeatureController;
use App\Http\Controllers\Painel\GoogleController;
use App\Http\Controllers\Painel\MelhorEnvioController;
use App\Http\Controllers\Painel\MercadoLivreController;
use App\Http\Controllers\Painel\NewsletterController;
use App\Http\Controllers\Painel\OrderCommentController;
use App\Http\Controllers\Painel\OrderController;
use App\Http\Controllers\Painel\ProductCommentController;
use App\Http\Controllers\Painel\ProductController;
use App\Http\Controllers\Painel\UserController;
use App\Http\Controllers\Painel\VoucherController;
use Illuminate\Support\Facades\Route;

Route::prefix('painel')->name('painel.')->controller(AdminLoginController::class)->group(function () {
    Route::get('/login', 'index')->name('login.form');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware('admin')->name('painel.')->prefix('painel')->group(static function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('/features', [FeatureController::class, 'index'])->name('features.index');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/newsletters', [NewsletterController::class, 'index'])->name('newsletters.index');
    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/products/comments', [ProductCommentController::class, 'index'])->name('products.comments.index');
    Route::get('/orders/comments', [OrderCommentController::class, 'index'])->name('orders.comments.index');
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/notifications/read', [DashboardController::class, 'read'])->name('notifications.read');
});

Route::middleware('admin')->prefix('painel')->controller(MercadoLivreController::class)->group(function () {
    Route::get('/ml', 'index')->name('painel.mercadolivre.dashboard.index');
    Route::get('/ml/products/load', 'productsLoad')->name('painel.mercadolivre.products.load');
    Route::get('/ml/accounts', 'accounts')->name('painel.mercadolivre.accounts.index');
    Route::get('/ml/comments', 'comments')->name('painel.mercadolivre.comments.index');
    Route::get('/ml/comments/history', 'commentsHistory')->name('painel.mercadolivre.comments.history');
    Route::get('/ml/answers', 'answers')->name('painel.mercadolivre.answers.index');
    Route::get('/ml/sellers', 'sellers')->name('painel.mercadolivre.sellers.index');
});

Route::middleware('admin')->prefix('painel')->controller(MelhorEnvioController::class)->group(function () {
    Route::get('/me/accounts', 'index')->name('painel.melhorenvio.index');
});

Route::middleware('admin')->prefix('painel')->controller(GoogleController::class)->group(function () {
    Route::get('/google', 'index')->name('painel.google.index');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('painel.google.auth.callback');
});
