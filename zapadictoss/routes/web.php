<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PromocionController;

Route::get('/', fn() => redirect()->route('productos.index'));

Route::resource('productos', ProductoController::class);
Route::get('productos-pdf', [ProductoController::class, 'pdf'])->name('productos.pdf');
Route::resource('promociones', PromocionController::class);