<?php

use App\Http\Controllers\Admin\BrandController;
use Illuminate\Support\Facades\Route;

Route::get('brands/trash', [BrandController::class, 'trash'])->name('brands.trash');
Route::get('brands/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');
Route::delete('brands/{id}/force-delete', [BrandController::class, 'forceDelete'])->name('brands.forceDelete');
Route::resource('brands', BrandController::class);