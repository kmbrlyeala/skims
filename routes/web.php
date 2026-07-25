<?php

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
            Route::post('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
            // Supply Chain Routes
            Route::resource('suppliers', \App\Http\Controllers\Admin\SupplyChain\SupplierController::class)->except(['create', 'edit']);
            Route::post('suppliers/{supplier}/link-product', [\App\Http\Controllers\Admin\SupplyChain\SupplierController::class, 'linkProduct'])->name('suppliers.link-product');
            Route::delete('suppliers/{supplier}/products/{product}/unlink', [\App\Http\Controllers\Admin\SupplyChain\SupplierController::class, 'unlinkProduct'])->name('suppliers.unlink-product');

            Route::resource('products', \App\Http\Controllers\Admin\SupplyChain\ProductController::class)->except(['show']);

            Route::resource('purchase-requests', \App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class)->except(['show', 'edit', 'update', 'destroy']);
            Route::post('purchase-requests/{purchaseRequest}/approve', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
            Route::post('purchase-requests/{purchaseRequest}/reject', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');

            Route::get('purchase-orders', [\App\Http\Controllers\Admin\SupplyChain\PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
            Route::get('purchase-orders/{purchaseOrder}', [\App\Http\Controllers\Admin\SupplyChain\PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
        });

    /*
    |----------------------------------------------------------------------
    | Inventory Manager Routes
    |----------------------------------------------------------------------
    */
    Route::middleware(EnsureUserRole::class.':inventory_manager')
        ->prefix('inventory-manager')
        ->name('inventory-manager.')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'inventoryManager'])->name('dashboard');

            Route::get('goods-receipts/create', [\App\Http\Controllers\InventoryManager\GoodsReceiptController::class, 'create'])->name('goods-receipts.create');
            Route::post('goods-receipts', [\App\Http\Controllers\InventoryManager\GoodsReceiptController::class, 'store'])->name('goods-receipts.store');

            Route::get('supply-inventory', [\App\Http\Controllers\InventoryManager\InventoryController::class, 'index'])->name('supply-inventory.index');
            Route::patch('supply-inventory/{inventory}', [\App\Http\Controllers\InventoryManager\InventoryController::class, 'update'])->name('supply-inventory.update');
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
            Route::get('/purchase-requests', [\App\Http\Controllers\Supplier\PurchaseRequestController::class, 'index'])->name('purchase-requests.index');
            Route::post('/purchase-requests/{purchaseRequest}/approve', [\App\Http\Controllers\Supplier\PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
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
