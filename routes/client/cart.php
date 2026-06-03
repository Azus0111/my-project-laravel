<?php
use App\Http\Controllers\Client\CartController;
use Illuminate\Support\Facades\Route;

Route::prefix('cart')->controller(CartController::class)->name('cart.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/{id}/add', 'addToCart')->name('add');
    Route::post('/{id}/decrease', 'decreaseQuantity')->name('decrease');
    Route::post('/{id}/remove', 'removeCart')->name('remove');
    Route::get('/checkout', 'checkout')->name('checkout');
    Route::post('/save', 'save')->name('save');
});