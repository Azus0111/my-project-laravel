<?php
use App\Http\Controllers\Client\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->controller(ProductController::class)->name('products.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{slug}', 'show')->name('show');
});

