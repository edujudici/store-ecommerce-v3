<?php

use App\Http\Controllers\DynamicjsController;

Route::name('dynamicjs.')->prefix('dynamicjs')->group(static function () {
    Route::get('base.js', [DynamicjsController::class, 'base'])->name('base.js');
});
