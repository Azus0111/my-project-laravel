<?php
use App\Http\Controllers\Client\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->controller(OrderController::class)->name('orders.')->group(function () {
    Route::get('/lookup', 'lookup')->name('lookup');
});