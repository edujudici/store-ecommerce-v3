<?php

use App\Http\Controllers\DynamicjsController;
use Illuminate\Support\Facades\Route;

Route::name('dynamicjs.')->prefix('dynamicjs')->group(static function () {
    Route::get('base.js', [DynamicjsController::class, 'base'])->name('base.js');
});
