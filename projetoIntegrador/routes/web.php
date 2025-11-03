<?php

use App\Http\Controllers\Admin\ProductRequirementController;
use App\Http\Controllers\Admin\GameImageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DeveloperController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CarouselController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->group(function(){
    Route::resource('developers', DeveloperController::class)->except(['show']);
    Route::resource('publishers', PublisherController::class)->except(['show']);
    Route::resource('platforms', PlatformController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('games', GameController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);


    // Rota para MOSTRAR o formulário de upload de imagens
    Route::get('/games/{game}/images', [GameImageController::class, 'index'])->name('games.images.index');
    
    // Rota para SALVAR a nova imagem
    Route::post('/games/{game}/images', [GameImageController::class, 'store'])->name('games.images.store');
    
    // Rota para DELETAR uma imagem
    Route::delete('/game-images/{image}', [GameImageController::class, 'destroy'])->name('images.destroy');
    
    
    //carrossel
    
    Route::get('/carousel', [CarouselController::class, 'index'])->name('carousel');
    Route::post('/carousel', [CarouselController::class, 'store'])->name('carousel.store');
    Route::put('/carousel/slide/{slide}', [CarouselController::class, 'update'])->name('update');
    Route::put('/carousel/slide/{slide}', [CarouselController::class, 'update'])
         ->name('carousel.update');
    
    // Rota para MOSTRAR o formulário de edição de requisitos
    Route::get('/products/{product}/requirements', [ProductRequirementController::class, 'edit'])
         ->name('products.requirements.edit');
    
    // Rota para SALVAR os dados do formulário
    Route::post('/products/{product}/requirements', [ProductRequirementController::class, 'store'])
         ->name('products.requirements.store');
    
    // Rota para MOSTRAR o formulário de criação
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products/{product}', [HomeController::class, 'show'])->name('products.show');







Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Todas as suas rotas de admin aqui
});

require __DIR__.'/auth.php';
