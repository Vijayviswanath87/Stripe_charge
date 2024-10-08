<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::post('/purchase/{id}', [ProductController::class, 'purchase'])->name('purchase');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

Route::get('/checkout/success', [ProductController::class, 'checkoutSuccess'])->name('checkout.success');
Route::get('/checkout/cancel', [ProductController::class, 'checkoutCancel'])->name('checkout.cancel');
Route::post('/checkout', [ProductController::class, 'processPayment'])->name('checkout.process');
Route::get('/checkout/complete', [ProductController::class, 'completePayment'])->name('checkout.complete');



