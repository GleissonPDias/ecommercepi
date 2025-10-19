<?php

use App\Http\Controllers\ProductRequirementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rota para MOSTRAR o formulário de edição de requisitos
Route::get('/products/{product}/requirements', [ProductRequirementController::class, 'edit'])
     ->name('products.requirements.edit');

// Rota para SALVAR os dados do formulário
Route::post('/products/{product}/requirements', [ProductRequirementController::class, 'store'])
     ->name('products.requirements.store');

// Rota para MOSTRAR o formulário de criação
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

// Rota para SALVAR o novo produto
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// Rota para LISTAR os produtos (bom para testar)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
