<?php

use App\Http\Controllers\Admin\AdminInventoryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CatalogController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Supplier\SupplierOrderController;
use App\Http\Controllers\SupplierInventoryController;
use App\Http\Middleware\EnsureUserRole;
use App\Http\Middleware\RedirectByRole;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Generic dashboard — redirects to role-specific dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard')->middleware(RedirectByRole::class);

    /*
    |----------------------------------------------------------------------
    | Admin Routes
    |----------------------------------------------------------------------
    */
    Route::middleware(EnsureUserRole::class.':admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'admin'])->name('dashboard');
            Route::get('/users', [UserController::class, 'index'])->name('users');
            Route::put('/users/{user}', [UserController::class, 'updateRole'])->name('users.updateRole');
            Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
            Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory');
        });

    /*
    |----------------------------------------------------------------------
    | Supplier Routes
    |----------------------------------------------------------------------
    */
    Route::middleware(EnsureUserRole::class.':supplier')
        ->prefix('supplier')
        ->name('supplier.')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'supplier'])->name('dashboard');
            Route::get('/inventory', [SupplierInventoryController::class, 'index'])->name('inventory');
            Route::post('/inventory', [SupplierInventoryController::class, 'store'])->name('inventory.store');
            Route::put('/inventory/{item}', [SupplierInventoryController::class, 'update'])->name('inventory.update');
            Route::delete('/inventory/{item}', [SupplierInventoryController::class, 'destroy'])->name('inventory.destroy');
            Route::get('/orders', [SupplierOrderController::class, 'index'])->name('orders');
            Route::put('/orders/{orderItem}', [SupplierOrderController::class, 'updateStatus'])->name('orders.updateStatus');
        });

    /*
    |----------------------------------------------------------------------
    | Customer Routes
    |----------------------------------------------------------------------
    */
    Route::middleware(EnsureUserRole::class.':customer')
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'customer'])->name('dashboard');
            Route::get('/shop', [CatalogController::class, 'index'])->name('shop');
            Route::get('/cart', [CartController::class, 'index'])->name('cart');
            Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
            Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
            Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
            Route::get('/orders', [OrderController::class, 'index'])->name('orders');
            Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
            Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        });
});
