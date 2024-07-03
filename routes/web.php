<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('login', function () {
    return view('login');
})->name('login');

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/admin/user', function () {
    return view('user');
})->name('user');


// untuk produk
use App\Http\Controllers\ProductController;

Route::get('/admin/product', [ProductController::class, 'index'])->name('product');
