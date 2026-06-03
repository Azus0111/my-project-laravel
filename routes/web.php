<?php

use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    require __DIR__ . '/admin/auth.php';
    Route::middleware(['auth', 'check.admin'])->group(function () {
        require __DIR__ . '/admin/home.php';
        require __DIR__ . '/admin/brand.php';
        require __DIR__ . '/admin/category.php';
        require __DIR__ . '/admin/product.php';
        require __DIR__ . '/admin/user.php';
        require __DIR__ . '/admin/post.php';
        require __DIR__ . '/admin/order.php';
    });
});

Route::prefix('/')->group(function () {
    require __DIR__ . '/client/home.php';
    require __DIR__ . '/client/product.php';
    require __DIR__ . '/client/cart.php';
    require __DIR__ . '/client/order.php';
});

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->name('password.update');