<?php
use App\Http\Controllers\Client\HomeController;
use Illuminate\Support\Facades\Route;

Route::resource('', HomeController::class);