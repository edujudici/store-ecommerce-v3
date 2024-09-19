<?php

use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\FaqController;
use App\Http\Controllers\API\FeatureController;
use App\Http\Controllers\API\Google\GoogleController;
use App\Http\Controllers\API\MelhorEnvio\FreightController;
use App\Http\Controllers\API\MelhorEnvio\MelhorEnvioController;
use App\Http\Controllers\API\MercadoLivre\MercadoLivreAnswerController;
use App\Http\Controllers\API\MercadoLivre\MercadoLivreCommentController;
use App\Http\Controllers\API\MercadoLivre\MercadoLivreController;
use App\Http\Controllers\API\MercadoLivre\MercadoLivreLoadController;
use App\Http\Controllers\API\MercadoLivre\MercadoLivreNotificationController;
use App\Http\Controllers\API\NewsletterController;
use App\Http\Controllers\API\OrderCommentController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PayController;
use App\Http\Controllers\API\PictureController;
use App\Http\Controllers\API\ProductCommentController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductExclusiveController;
use App\Http\Controllers\API\ProductRelatedController;
use App\Http\Controllers\API\ProductSpecificationController;
use App\Http\Controllers\API\ProductVisitedController;
use App\Http\Controllers\API\SellerController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VoucherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')->group(static function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::get('banners', [BannerController::class, 'index'])->name('banners.index');
    Route::post('banners', [BannerController::class, 'store'])->name('banners.store');
    Route::delete('banners', [BannerController::class, 'destroy'])->name('banners.destroy');

    Route::get('features', [FeatureController::class, 'index'])->name('features.index');
    Route::post('features', [FeatureController::class, 'store'])->name('features.store');
    Route::delete('features', [FeatureController::class, 'destroy'])->name('features.destroy');

    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('categories', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
    Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
    Route::delete('brands', [BrandController::class, 'destroy'])->name('brands.destroy');

    Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::post('companies', [CompanyController::class, 'store'])->name('companies.store');

    Route::get('newsletters', [NewsletterController::class, 'index'])->name('newsletters.index');
    Route::post('newsletters', [NewsletterController::class, 'store'])->name('newsletters.store');

    Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');
    Route::post('faqs', [FaqController::class, 'store'])->name('faqs.store');
    Route::delete('faqs', [FaqController::class, 'destroy'])->name('faqs.destroy');

    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::delete('contacts', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('contacts/answer', [ContactController::class, 'answer'])->name('contacts.answer');

    Route::get('vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
    Route::delete('vouchers', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
    Route::post('vouchers/valid', [VoucherController::class, 'valid'])->name('vouchers.valid');
    Route::post('vouchers/findByUser', [VoucherController::class, 'findByUser'])->name('vouchers.findByUser');

    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::delete('products', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('products/format', [ProductController::class, 'indexFormat'])->name('products.index.format');
    Route::get('products/show', [ProductController::class, 'show'])->name('products.show');
    Route::get('products/findByName', [ProductController::class, 'findByName'])->name('products.findByName');

    Route::get('products/comments', [ProductCommentController::class, 'index'])->name('products.comments.index');
    Route::get('products/comments/all', [ProductCommentController::class, 'indexAll'])->name('products.comments.indexAll');
    Route::post('products/comments', [ProductCommentController::class, 'store'])->name('products.comments.store');
    Route::delete('products/comments', [ProductCommentController::class, 'destroy'])->name('products.comments.destroy');

    Route::get('pictures', [PictureController::class, 'index'])->name('pictures.index');
    Route::get('specifications', [ProductSpecificationController::class, 'index'])->name('productsSpecifications.index');
    Route::get('exclusives', [ProductExclusiveController::class, 'index'])->name('productsExclusives.index');
    Route::get('relateds', [ProductRelatedController::class, 'index'])->name('productsRelateds.index');
    Route::post('relateds/format', [ProductRelatedController::class, 'indexFormat'])->name('productsRelateds.index.format');
    Route::get('visiteds', [ProductVisitedController::class, 'index'])->name('productsVisiteds.index');

    Route::post('freight/calculate', [FreightController::class, 'calculate'])->name('freight.calculate');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');

    Route::get('orders/comments', [OrderCommentController::class, 'index'])->name('orders.comments.index');
    Route::get('orders/comments/all', [OrderCommentController::class, 'indexAll'])->name('orders.comments.indexAll');
    Route::post('orders/comments', [OrderCommentController::class, 'store'])->name('orders.comments.store');
    Route::delete('orders/comments', [OrderCommentController::class, 'destroy'])->name('orders.comments.destroy');

    Route::get('sellers', [SellerController::class, 'index'])->name('sellers.index');
    Route::delete('sellers', [SellerController::class, 'destroy'])->name('sellers.destroy');
    Route::post('sellers/search', [SellerController::class, 'search'])->name('sellers.search');

    Route::post('dashboard/shoppers', [DashboardController::class, 'totalShoppers'])->name('dashboard.totalShoppers');
    Route::post('dashboard/revenue', [DashboardController::class, 'totalRevenue'])->name('dashboard.totalRevenue');
    Route::post('dashboard/orders-overview', [DashboardController::class, 'ordersOverview'])->name('dashboard.ordersOverview');
    Route::post('dashboard/sales-year', [DashboardController::class, 'salesYear'])->name('dashboard.salesYear');
    Route::post('dashboard/recent-orders', [DashboardController::class, 'recentOrders'])->name('dashboard.recentOrders');
    Route::post('dashboard/new-shoppers', [DashboardController::class, 'newShoppers'])->name('dashboard.newShoppers');
    Route::post('dashboard/top-products', [DashboardController::class, 'topProducts'])->name('dashboard.topProducts');

    // routes mercadolivre
    Route::post('ml/loads/single-product', [MercadoLivreLoadController::class, 'singleProduct'])->name('load.single.product');
    Route::post('ml/loads/multiple-products', [MercadoLivreLoadController::class, 'multipleProducts'])->name('load.multiple.products');
    Route::post('ml/loads/questions', [MercadoLivreLoadController::class, 'questions'])->name('load.questions');
    Route::get('ml/loads/products/history', [MercadoLivreLoadController::class, 'indexProduct'])->name('load.product.history');
    Route::get('ml/loads/questions/history', [MercadoLivreLoadController::class, 'indexQuestion'])->name('load.question.history');
    Route::get('ml/dashboard', [MercadoLivreController::class, 'index'])->name('mercadolivre.dashboard.index');
    Route::get('ml/accounts', [MercadoLivreController::class, 'index'])->name('mercadolivre.accounts.index');
    Route::post('ml/accounts', [MercadoLivreController::class, 'store'])->name('mercadolivre.accounts.store');
    Route::delete('ml/accounts', [MercadoLivreController::class, 'destroy'])->name('mercadolivre.accounts.destroy');
    Route::get('ml/auth', [MercadoLivreController::class, 'auth'])->name('mercadolivre.auth.index');
    Route::get('ml/me', [MercadoLivreController::class, 'getMyInfoData'])->name('mercadolivre.me.data');
    Route::get('ml/comments', [MercadoLivreCommentController::class, 'index'])->name('mercadolivre.comments.index');
    Route::post('ml/comments', [MercadoLivreCommentController::class, 'store'])->name('mercadolivre.comments.store');
    Route::delete('ml/comments', [MercadoLivreCommentController::class, 'destroy'])->name('mercadolivre.comments.destroy');
    Route::any('ml/notifications', [MercadoLivreNotificationController::class, 'store'])->name('mercadolivre.notify.store');
    Route::get('ml/answers', [MercadoLivreAnswerController::class, 'index'])->name('mercadolivre.answers.index');
    Route::post('ml/answers', [MercadoLivreAnswerController::class, 'store'])->name('mercadolivre.answers.store');
    Route::delete('ml/answers', [MercadoLivreAnswerController::class, 'destroy'])->name('mercadolivre.answers.destroy');

    // routes mercadopago
    Route::any('mp/notifications', [PayController::class, 'notification'])->name('notifications.ipn');

    // routes melhor envio
    Route::get('me/auth', [MelhorEnvioController::class, 'auth'])->name('melhorenvio.auth');

    // routes google api
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.auth');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.auth.callback');
    Route::get('google/products/all', [GoogleController::class, 'getProductsAll'])->name('google.products.indexAll');
    Route::get('google/products', [GoogleController::class, 'getSingleProduct'])->name('google.products.index');
    Route::post('google/products', [GoogleController::class, 'singleProduct'])->name('google.products.store');
    Route::patch('google/products', [GoogleController::class, 'updateSingleProduct'])->name('google.products.update');
    Route::delete('google/products', [GoogleController::class, 'deleteSingleProduct'])->name('google.products.destroy');
    Route::get('google/products/history', [GoogleController::class, 'historyProduct'])->name('google.products.index.history');
    Route::post('google/products/all', [GoogleController::class, 'multipleProducts'])->name('google.products.storeMultiple');
});
