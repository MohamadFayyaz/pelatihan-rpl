<?php

use Illuminate\Support\Facades\Route;



Route::get('login', function () {
    return view('login');
})->name('login');

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/admin/user', function () {
    return view('user');
})->name('user');


// home & produk
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/admin/product', [ProductController::class, 'index'])->name('product');

Route::post('/bayar', [HomeController::class, 'pay'])->name('bayar');
