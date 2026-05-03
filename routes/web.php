<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\VentaController;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('login/{type}', [AuthController::class, 'showLoginForm'])->name('login.type');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');
    Route::get('registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('registro', [AuthController::class, 'register'])->name('register.store');
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return auth()->user()->role === 'admin'
            ? redirect()->route('productos.index')
            : redirect()->route('productos.catalogo');
    });

    Route::get('catalogo', [ProductoController::class, 'catalogo'])->name('productos.catalogo');
    Route::post('ventas', [VentaController::class, 'store'])->middleware('role:normal')->name('ventas.store');
    Route::get('ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');

    Route::middleware('role:admin')->group(function () {
        Route::get('ventas', [VentaController::class, 'index'])->name('ventas.index');
        Route::delete('ventas/historial', [VentaController::class, 'destroyHistory'])->name('ventas.destroy-history');
        Route::resource('productos', ProductoController::class);
        Route::get('productos-pdf', [ProductoController::class, 'pdf'])->name('productos.pdf');
        Route::resource('promociones', PromocionController::class)
            ->parameters(['promociones' => 'promocion']);
    });
});
