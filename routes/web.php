<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthAdmin;

// Authentication Routes
Auth::routes();

// Frontend Routes
Route::controller(App\Http\Controllers\HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home.index');
    Route::get('/contact-us', 'contact')->name('home.contact');
    Route::post('/contact/store', 'contact_store')->name('home.contact.store');
    Route::get('/search', 'search')->name('home.search');
    Route::get('/about', 'about')->name('home.about');
    Route::get('/terms-conditional', 'terms')->name('home.terms');
    Route::get('/privacy-policy', 'policy')->name('home.policy');
});

// Shop Routes
Route::controller(App\Http\Controllers\ShopController::class)->group(function () {
    Route::get('/shop', 'index')->name('shop.index');
    Route::get('/shop/{product_slug}', 'product_details')->name('shop.product.details');
});

// Cart Routes
Route::controller(App\Http\Controllers\CartController::class)->prefix('cart')->group(function () {
    // Basic Cart Operations
    Route::get('/', 'index')->name('cart.index');
    Route::post('/store', 'add_cart')->name('cart.store');

    // Quantity Management
    Route::put('/increase-qty/{rowId}', 'increase_cart_qty')->name('cart.increase.qty');
    Route::put('/decrease-qty/{rowId}', 'decrease_cart_qty')->name('cart.decrease.qty');
    Route::delete('/delete/{rowId}', 'destroy_cart_qty')->name('cart.delete.qty');
    Route::delete('/clear', 'clear_cart')->name('cart.clear');

    // Discount Management
    Route::post('/apply-discount', 'apply_disc')->name('cart.disc.add');
    Route::post('/remove-discount', 'delete_disc')->name('cart.disc.delete');

    // Checkout Process
    Route::get('/checkout', 'checkout')->name('cart.checkout');
    Route::post('/place-an-order', 'place_an_order')->name('cart.order');
    Route::get('/order-confirmation', 'order_confirmation')->name('cart.confirm');
});

// Wishlist Routes
Route::controller(App\Http\Controllers\WishlistController::class)->prefix('wishlist')->group(function () {
    Route::get('/', 'index')->name('wishlist.index');
    Route::post('/store', 'add_wishlist')->name('wishlist.store');
    Route::post('/move-to-cart/{rowId}', 'move_to_cart')->name('wishlist.move.to.cart');
    Route::delete('/delete/{rowId}', 'destroy_wishlist_qty')->name('wishlist.delete.qty');
    Route::delete('/clear', 'clear_wishlist')->name('wishlist.clear');
});

// User Dashboard Routes
Route::middleware(['auth'])->controller(App\Http\Controllers\UserController::class)
    ->prefix('account-dashboard')->as('user.')->group(function () {
        Route::get('/', 'index')->name('index');

        Route::get('/orders', 'orders')->name('orders');
        Route::get('/orders/{order_id}/details', 'order_details')->name('order.details');
        Route::put('/cancel', 'order_cancel')->name('order.cancel');

        Route::get('/wishlist', 'wishlist')->name('wishlist');

        Route::get('/details', 'setting')->name('details');
        Route::post('/setting', 'setting_update')->name('setting');

        Route::get('/address', 'address')->name('address');
        Route::get('/address-create', 'address_create')->name('address.create');
        Route::post('/address-store', 'address_store')->name('address.store');
        Route::post('/address/{id}/default', 'setDefault')->name('address.setDefault');

    });

// Admin Routes
Route::middleware(['auth', AuthAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::controller(App\Http\Controllers\AdminController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/search', 'search')->name('search');
        Route::get('/users', 'users')->name('users');
        Route::get('/setting', 'setting')->name('setting');
        Route::post('/setting-update', 'setting_update')->name('setting.update');
    });

    // Category Management
    Route::controller(App\Http\Controllers\AdminController::class)->prefix('category')->group(function () {
        Route::get('/', 'categories')->name('categories');
        Route::get('/create', 'category_create')->name('category.create');
        Route::post('/store', 'category_store')->name('category.store');
        Route::get('/edit/{id}', 'category_edit')->name('category.edit');
        Route::put('/update', 'category_update')->name('category.update');
        Route::delete('/delete/{id}', 'category_destroy')->name('category.delete');
    });

    // Product Management
    Route::controller(App\Http\Controllers\AdminController::class)->prefix('product')->group(function () {
        Route::get('/', 'products')->name('products');
        Route::get('/create', 'product_create')->name('product.create');
        Route::post('/store', 'product_store')->name('product.store');
        Route::get('/edit/{id}', 'product_edit')->name('product.edit');
        Route::put('/update', 'product_update')->name('product.update');
        Route::delete('/delete/{id}', 'product_destroy')->name('product.delete');
    });

    // Discount Management
    Route::controller(App\Http\Controllers\AdminController::class)->prefix('discount')->group(function () {
        Route::get('/', 'discounts')->name('discounts');
        Route::get('/create', 'discount_create')->name('discount.create');
        Route::post('/store', 'discount_store')->name('discount.store');
        Route::get('/edit/{id}', 'discount_edit')->name('discount.edit');
        Route::put('/update', 'discount_update')->name('discount.update');
        Route::delete('/delete/{id}', 'discount_destroy')->name('discount.delete');
    });

    // Order Management
    Route::controller(App\Http\Controllers\AdminController::class)->prefix('order')->group(function () {
        Route::get('/', 'orders')->name('orders');
        Route::get('/{order_id}/details', 'orders_details')->name('order.details');
        Route::put('/update', 'update_status')->name('order.update');
    });

    // Slide Management
    Route::controller(App\Http\Controllers\AdminController::class)->prefix('slide')->group(function () {
        Route::get('/', 'slides')->name('slides');
        Route::get('/create', 'slide_create')->name('slide.create');
        Route::post('/store', 'slide_store')->name('slide.store');
        Route::get('/edit/{id}', 'slide_edit')->name('slide.edit');
        Route::put('/update', 'slide_update')->name('slide.update');
        Route::delete('/delete/{id}', 'slide_destroy')->name('slide.delete');
    });
});
