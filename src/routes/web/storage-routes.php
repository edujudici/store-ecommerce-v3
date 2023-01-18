<?php

use App\Http\Controllers\StorageController;

Route::name('storage.')->group(static function () {
    Route::get('storage/{filename?}', [StorageController::class, 'show'])->name('upload.show');
});
