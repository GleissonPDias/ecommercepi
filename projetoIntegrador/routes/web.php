<?php

use App\Http\Controllers\Admin\ProductRequirementController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DeveloperController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CarouselController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products/{product}', [HomeController::class, 'show'])->name('products.show');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/admin/games/create', [GameController::class, 'create'])->name('admin.games.create'); 
Route::post('/admin/games', [GameController::class, 'store'])->name('admin.games.store'); 

Route::get('/admin/developers/create', [DeveloperController::class, 'create'])->name('admin.developers.create');
Route::post('/admin/developers', [DeveloperController::class, 'store'])->name('admin.developers.store');

Route::get('/admin/publishers/create', [PublisherController::class, 'create'])->name('admin.publishers.create');
Route::post('/admin/publishers', [PublisherController::class, 'store'])->name('admin.publishers.store');

Route::get('/admin/platforms/create', [PlatformController::class, 'create'])->name('admin.platforms.create');
Route::post('/admin/platforms', [PlatformController::class, 'store'])->name('admin.platforms.store');


//carrossel

Route::get('/admin/carousel', [CarouselController::class, 'index'])->name('admin.carousel');
Route::post('/admin/carousel', [CarouselController::class, 'store'])->name('admin.carousel.store');
Route::put('/admin/carousel/slide/{slide}', [CarouselController::class, 'update'])->name('admin.update');
Route::put('/admin/carousel/slide/{slide}', [CarouselController::class, 'update'])
     ->name('admin.carousel.update');

// Rota para MOSTRAR o formulário de edição de requisitos
Route::get('admin/products/{product}/requirements', [ProductRequirementController::class, 'edit'])
     ->name('admin.products.requirements.edit');

// Rota para SALVAR os dados do formulário
Route::post('admin/products/{product}/requirements', [ProductRequirementController::class, 'store'])
     ->name('admin.products.requirements.store');

// Rota para MOSTRAR o formulário de criação
Route::get('admin/products/create', [ProductController::class, 'create'])
    ->name('admin.products.create');

// Rota para SALVAR o novo produto
Route::post('admin/products', [ProductController::class, 'store'])->name('admin.products.store');

// Rota para LISTAR os produtos (bom para testar)
Route::get('admin/products', [ProductController::class, 'index'])->name('admin.products.index');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
