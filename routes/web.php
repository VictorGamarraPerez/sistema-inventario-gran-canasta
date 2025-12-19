<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Redirigir la raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación (sin middleware auth)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/verification', [AuthController::class, 'showVerificationForm'])->name('verification.form');
    Route::post('/verification', [AuthController::class, 'verify'])->name('verification.verify');
    Route::post('/verification/resend', [AuthController::class, 'resendCode'])->name('verification.resend');
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // Dashboard - Todos los roles
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas de productos
    Route::get('products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index')->middleware('role:administrador,supervisor,almacen,consulta');
    Route::get('products/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show')->middleware('role:administrador,supervisor,almacen,consulta');
    Route::get('products/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('products.create')->middleware('role:administrador,supervisor,almacen');
    Route::post('products', [\App\Http\Controllers\ProductController::class, 'store'])->name('products.store')->middleware('role:administrador,supervisor,almacen');
    Route::get('products/{product}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit')->middleware('role:administrador,supervisor,almacen');
    Route::put('products/{product}', [\App\Http\Controllers\ProductController::class, 'update'])->name('products.update')->middleware('role:administrador,supervisor,almacen');
    Route::delete('products/{product}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy')->middleware('role:administrador,supervisor,almacen');
    
    // Ruta para crear categorías vía AJAX
    Route::post('/categories/store-ajax', [\App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store.ajax');
    
    // Ruta para crear proveedores vía AJAX
    Route::post('/suppliers/store-ajax', [\App\Http\Controllers\SupplierController::class, 'store'])->name('suppliers.store.ajax');
    
    // Ruta para actualizar proveedores vía AJAX
    Route::put('/suppliers/{supplier}/update-ajax', [\App\Http\Controllers\SupplierController::class, 'update'])->name('suppliers.update.ajax');
    
    // Rutas de entradas
    Route::get('entries', [\App\Http\Controllers\EntryController::class, 'index'])->name('entries.index')->middleware('role:administrador,supervisor,almacen,consulta');
    Route::get('entries/{entry}', [\App\Http\Controllers\EntryController::class, 'show'])->name('entries.show')->middleware('role:administrador,supervisor,almacen,consulta');
    Route::get('entries/create', [\App\Http\Controllers\EntryController::class, 'create'])->name('entries.create')->middleware('role:administrador,supervisor,almacen');
    Route::post('entries', [\App\Http\Controllers\EntryController::class, 'store'])->name('entries.store')->middleware('role:administrador,supervisor,almacen');
    Route::get('entries/{entry}/edit', [\App\Http\Controllers\EntryController::class, 'edit'])->name('entries.edit')->middleware('role:administrador,supervisor,almacen');
    Route::put('entries/{entry}', [\App\Http\Controllers\EntryController::class, 'update'])->name('entries.update')->middleware('role:administrador,supervisor,almacen');
    Route::delete('entries/{entry}', [\App\Http\Controllers\EntryController::class, 'destroy'])->name('entries.destroy')->middleware('role:administrador,supervisor,almacen');
    
    // Rutas de salidas
    Route::get('exits', [\App\Http\Controllers\ExitController::class, 'index'])->name('exits.index')->middleware('role:administrador,supervisor,almacen,consulta');
    Route::get('exits/{exit}', [\App\Http\Controllers\ExitController::class, 'show'])->name('exits.show')->middleware('role:administrador,supervisor,almacen,consulta');
    Route::get('exits/create', [\App\Http\Controllers\ExitController::class, 'create'])->name('exits.create')->middleware('role:administrador,supervisor,almacen');
    Route::post('exits', [\App\Http\Controllers\ExitController::class, 'store'])->name('exits.store')->middleware('role:administrador,supervisor,almacen');
    Route::get('exits/{exit}/edit', [\App\Http\Controllers\ExitController::class, 'edit'])->name('exits.edit')->middleware('role:administrador,supervisor,almacen');
    Route::put('exits/{exit}', [\App\Http\Controllers\ExitController::class, 'update'])->name('exits.update')->middleware('role:administrador,supervisor,almacen');
    Route::delete('exits/{exit}', [\App\Http\Controllers\ExitController::class, 'destroy'])->name('exits.destroy')->middleware('role:administrador,supervisor,almacen');
    
    // Rutas de reportes
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index')->middleware('role:administrador,supervisor,consulta');
    Route::get('/reports/export/excel', [\App\Http\Controllers\ReportController::class, 'exportExcel'])->name('reports.export.excel')->middleware('role:administrador,supervisor,consulta');
    Route::get('/reports/export/pdf', [\App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.export.pdf')->middleware('role:administrador,supervisor,consulta');
    
    // Rutas de usuarios - Solo administrador
    Route::resource('users', \App\Http\Controllers\UserController::class)->middleware('role:administrador');
    
    // Rutas de perfil - Todos los roles
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    
    // Editar perfil - Solo administrador y supervisor
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit')->middleware('role:administrador,supervisor');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update')->middleware('role:administrador,supervisor');
    
    // Cambiar contraseña - Todos los roles
    Route::get('/profile/password', [\App\Http\Controllers\ProfileController::class, 'editPassword'])->name('profile.password');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Configuración - Todos ven su info
    Route::get('/settings', [\App\Http\Controllers\ProfileController::class, 'settings'])->name('settings');
    Route::post('/settings/preferences', [\App\Http\Controllers\ProfileController::class, 'updatePreferences'])->name('settings.preferences.update');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
