<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransWebhookController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/profil', 'profile')->name('profile');
Route::view('/visi-misi', 'vision-mission')->name('vision_mission');
Route::view('/ruang-lingkup', 'scope')->name('scope');
Route::view('/kontak', 'contact')->name('contact');

Route::get('/produk', function () {
    $products = Product::query()
        ->select(['id', 'name', 'slug', 'category', 'badge', 'unit', 'price', 'stock', 'image_url'])
        ->where('is_active', true)
        ->orderBy('category')
        ->orderBy('name')
        ->get();

    return view('products', [
        'products' => $products,
    ]);
})->name('products');

Route::get('/produk/{product:slug}', function (Product $product) {
    abort_unless($product->is_active, 404);

    $related = Product::query()
        ->select(['id', 'name', 'slug', 'category', 'badge', 'unit', 'price', 'stock', 'image_url'])
        ->where('is_active', true)
        ->where('category', $product->category)
        ->whereKeyNot($product->id)
        ->orderBy('name')
        ->take(4)
        ->get();

    return view('product-detail', [
        'product' => $product,
        'relatedProducts' => $related,
    ]);
})->name('product.detail');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart');
Route::post('/keranjang/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/keranjang/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/{order}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/payments/midtrans/notification', MidtransWebhookController::class)->name('payments.midtrans.notification');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::resource('members', AdminMemberController::class)
            ->except(['show']);

        Route::resource('products', AdminProductController::class)
            ->except(['show']);

        Route::resource('orders', AdminOrderController::class)
            ->only(['index', 'show', 'update']);
        Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');

        Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
        Route::post('/inventory', [AdminInventoryController::class, 'store'])->name('inventory.store');
    });

Route::redirect('/products', '/produk');
