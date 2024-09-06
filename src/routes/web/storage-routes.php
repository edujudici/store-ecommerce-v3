<?php

use App\Http\Controllers\StorageController;
use Illuminate\Support\Facades\Route;

Route::name('storage.')->group(static function () {
    Route::get('storage/{filename?}', [StorageController::class, 'show'])->name('upload.show');
});
