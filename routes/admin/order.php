<?php

use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('orders/trash', [OrderController::class, 'trash'])->name('orders.trash');
Route::get('orders/{id}/restore', [OrderController::class, 'restore'])->name('orders.restore');
Route::delete('orders/{id}/force-delete', [OrderController::class, 'forceDelete'])->name('orders.forceDelete');
Route::patch('orders/{id}/force-delete', [OrderController::class, 'forceDelete'])->name('orders.forceDelete');
Route::patch('orders/{id}/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');
Route::patch('orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
Route::resource('orders', OrderController::class);