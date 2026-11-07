<?php

use App\Http\Controllers\Storefront\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::name('biatech.')->group(function (): void {
    Route::get('/', [StorefrontController::class, 'home'])->name('home');
    Route::get('/produk', [StorefrontController::class, 'catalog'])->name('catalog');
    Route::get('/produk/{slug}', [StorefrontController::class, 'product'])->name('product');
    Route::get('/checkout/{slug}', [StorefrontController::class, 'checkout'])->name('checkout');
    Route::get('/pembayaran/{invoice}', [StorefrontController::class, 'payment'])->name('payment');
    Route::get('/pesanan/{invoice}', [StorefrontController::class, 'order'])->name('order');
    Route::get('/lacak-pesanan', [StorefrontController::class, 'track'])->name('track');
    Route::get('/bantuan', [StorefrontController::class, 'help'])->name('help');
});
