<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
Route::get('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');
Route::resource('categories', CategoryController::class);