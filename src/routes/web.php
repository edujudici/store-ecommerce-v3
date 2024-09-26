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


/**
 * Test search cep
 */
Route::get('/debug-token/{code}', function ($code) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sandbox.melhorenvio.com.br/oauth/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
{
  "grant_type": "authorization_code",
  "client_id": "3743",
  "client_secret": "J7fA5OxDnkpYyTcaNTtjDJDwktLYexrCUp4HaqD1",
  "redirect_uri": "https://imperiodomdf.com.br/api/me/auth",
  "code": "' . $code . '"
}
',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'User-Agent: ImperioDoMdf (edujudici@gmail.com)'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
});
