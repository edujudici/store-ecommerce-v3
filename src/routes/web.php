<?php

use Claudsonm\CepPromise\CepPromise;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$glob_file = __DIR__ . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . '*';
foreach (glob($glob_file) as $file) {
    include $file;
}

/**
 * Show PHP config
 */
/* Route::get('/phpinfo', static function () {
    phpinfo();
})->name('phpinfo'); */

/**
 * Test sentry for debug
 */
Route::get('/debug-sentry', static function () {
    throw new Exception('My first Sentry error!');
});

/**
 * E-mail render test
 */
Route::get('/debug-mail', function () {
    $mail = new App\Mail\AnswerContact([
        'title' => 'Contato Império do MDF',
        'body' => 'Gostaria de saber sobre a possibilidade de pintar o produto com mais de uma cor',
        'name' => 'Pessoa teste',
    ]);
    return $mail->render();
});

/**
 * Config display env
 */
Route::get('/debug-env/{env?}', function ($env) {
    return [
        'APP_ENV' => env('APP_ENV'),
        'MERCADO_PAGO_TOKEN' => env('MERCADO_PAGO_TOKEN'),
        $env => env($env),
    ];
});

/**
 * Test search cep
 */
Route::get('/debug-cep/{cep?}', function ($cep) {
    $address = CepPromise::fetch($cep);
    debug(['debug reponse address' => $address]);
    return $address;
});
