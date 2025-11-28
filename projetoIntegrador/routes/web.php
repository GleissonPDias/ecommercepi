<?php

use App\Http\Controllers\Admin\ProductRequirementController;
use App\Http\Controllers\Admin\GameImageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DeveloperController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\GameKeyController;
use App\Http\Controllers\Admin\CouponManagementController;
use App\Http\Controllers\Admin\GameModeController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;




    Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirectToProvider'])
    ->name('socialite.redirect');

// Rota que o Google/Facebook chama de volta
    Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])
    ->name('socialite.callback');
/*
|--------------------------------------------------------------------------
| Rotas de Admin (Protegidas)
|--------------------------------------------------------------------------
|
| Todas estas rotas exigem que o usuário esteja logado E seja um admin.
|
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {


    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('game-modes', GameModeController::class)->except(['show']);
    
    Route::resource('coupons', CouponManagementController::class)->except(['show']);
    Route::resource('developers', DeveloperController::class)->except(['show']);
    Route::resource('publishers', PublisherController::class)->except(['show']);
    Route::resource('platforms', PlatformController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('games', GameController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
    

    // --- Rotas de Imagens do Jogo ---
    Route::get('/games/{game}/images', [GameImageController::class, 'index'])->name('games.images.index');
    Route::post('/games/{game}/images', [GameImageController::class, 'store'])->name('games.images.store');
    Route::delete('/game-images/{image}', [GameImageController::class, 'destroy'])->name('images.destroy');
    
    // --- Rotas do Carrossel ---
    Route::get('/carousel', [CarouselController::class, 'index'])->name('carousel');
    Route::post('/carousel', [CarouselController::class, 'store'])->name('carousel.store');
    Route::put('/carousel/slide/{slide}', [CarouselController::class, 'update'])->name('carousel.update');
    
    // --- Rotas de Requisitos do Produto ---
    Route::get('/products/{product}/requirements', [ProductRequirementController::class, 'edit'])
         ->name('products.requirements.edit');
    Route::post('/products/{product}/requirements', [ProductRequirementController::class, 'store'])
         ->name('products.requirements.store');


    // -- Rotas das Keys --

    Route::get('/products/{product}/keys', [GameKeyController::class, 'index'])->name('products.keys.index');
    Route::post('/products/{product}/keys', [GameKeyController::class, 'store'])->name('products.keys.store');
    Route::delete('/keys/{key}', [GameKeyController::class, 'destroy'])->name('keys.destroy');
});

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Vitrine)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{product}', [HomeController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Rotas de Perfil e Dashboard (Padrão Breeze)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/favorites/toggle/{product}', [FavoriteController::class, 'toggle'])
         ->name('favorites.toggle');

    Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrinho/adicionar/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::post('/carrinho/increase/{cartItem}', [CartController::class, 'increase'])->name('cart.increase');
    Route::post('/carrinho/decrease/{cartItem}', [CartController::class, 'decrease'])->name('cart.decrease');
    Route::delete('/carrinho/remover/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');


    

    Route::post('/payment/checkout', [PaymentController::class, 'redirectToCheckout'])->name('payment.checkout');
    Route::get('/payment/success', [PaymentController::class, 'handleSuccess'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'handleCancel'])->name('payment.cancel');

    // routes/web.php (dentro do grupo 'auth')

// ... (rotas de profile, favorites, cart, payment) ...

// 👇 ADICIONE ESTA ROTA PARA APAGAR O CARTÃO 👇
    Route::delete('/payment/destroy/{paymentMethod}', [PaymentController::class, 'destroy'])->name('payment.destroy');


    Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
    Route::post('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');
});
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/plataforma/{platform:slug}', [CatalogController::class, 'showByPlatform'])
     ->name('catalog.platform');
/*
|--------------------------------------------------------------------------
| Rotas de Autenticação (Login, Registro, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';