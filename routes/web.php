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
            Route::get('/', [\App\Http\Controllers\Admin\UiMockupController::class, 'dashboard'])->name('dashboard');
            
            // Real Controllers for Users and Orders
            Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
            Route::put('/users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update-role');
            
            Route::get('/orders', [\App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('orders.index');
            
            // Supply Chain Routes
            Route::prefix('supply-chain')->group(function () {
                // Products
                Route::get('/products', [\App\Http\Controllers\Admin\SupplyChain\ProductController::class, 'index'])->name('products.index');
            });
            
            Route::resource('suppliers', \App\Http\Controllers\Admin\SupplyChain\SupplierController::class);
            
            Route::get('/categories', [\App\Http\Controllers\Admin\UiMockupController::class, 'categories'])->name('categories.index');
            Route::get('/customers', [\App\Http\Controllers\Admin\UiMockupController::class, 'customers'])->name('customers.index');
            Route::get('/shipping', [\App\Http\Controllers\Admin\UiMockupController::class, 'shipping'])->name('shipping.index');
            
            Route::get('/purchase-requests', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'index'])->name('purchase-requests.index');
            Route::get('/purchase-requests/create', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'create'])->name('purchase-requests.create');
            Route::post('/purchase-requests', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'store'])->name('purchase-requests.store');
            Route::post('/purchase-requests/{purchaseRequest}/approve', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
            Route::post('/purchase-requests/{purchaseRequest}/reject', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');

            Route::get('/purchase-orders', [\App\Http\Controllers\Admin\SupplyChain\PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
            Route::get('/purchase-orders/{purchaseOrder}', [\App\Http\Controllers\Admin\SupplyChain\PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
            Route::get('/inventory', [\App\Http\Controllers\Admin\UiMockupController::class, 'inventory'])->name('inventory.index');
            Route::get('/reports', [\App\Http\Controllers\Admin\UiMockupController::class, 'reports'])->name('reports.index');
            Route::get('/users', [\App\Http\Controllers\Admin\UiMockupController::class, 'users'])->name('users.index');
            Route::get('/settings', [\App\Http\Controllers\Admin\UiMockupController::class, 'settings'])->name('settings.index');
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

            // Real Supply Chain Controllers (Shared with Admin)
            Route::get('purchase-requests', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'index'])->name('purchase-requests.index');
            Route::get('purchase-requests/create', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'create'])->name('purchase-requests.create');
            Route::post('purchase-requests', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'store'])->name('purchase-requests.store');
            // Note: IMs don't have approve/reject routes; those are admin-only in the UI, but the controller checks roles anyway.

            Route::get('purchase-orders', [\App\Http\Controllers\Admin\SupplyChain\PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
            Route::get('purchase-orders/{purchaseOrder}', [\App\Http\Controllers\Admin\SupplyChain\PurchaseOrderController::class, 'show'])->name('purchase-orders.show');

            // UI Mockup Routes for IM
            Route::get('stock-movement', [\App\Http\Controllers\InventoryManager\UiMockupController::class, 'stockMovement'])->name('stock-movement.index');
            Route::get('low-stock', [\App\Http\Controllers\InventoryManager\UiMockupController::class, 'lowStock'])->name('low-stock.index');
            Route::get('reports', [\App\Http\Controllers\InventoryManager\UiMockupController::class, 'reports'])->name('reports.index');
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
            Route::get('/', [\App\Http\Controllers\Supplier\UiMockupController::class, 'dashboard'])->name('dashboard');
            Route::get('/purchase-requests', [\App\Http\Controllers\Supplier\PurchaseRequestController::class, 'index'])->name('purchase-requests.index');
            Route::get('/purchase-orders', [\App\Http\Controllers\Supplier\PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
            Route::post('/purchase-orders/{purchaseOrder}/status', [\App\Http\Controllers\Supplier\PurchaseOrderController::class, 'updateStatus'])->name('purchase-orders.update-status');
            
            // Replaced 'deliveries' with 'orders' for B2C customer orders
            Route::get('/orders', [\App\Http\Controllers\Supplier\SupplierOrderController::class, 'index'])->name('orders.index');
            Route::post('/orders/{orderItem}/status', [\App\Http\Controllers\Supplier\SupplierOrderController::class, 'updateStatus'])->name('orders.update-status');
            
            Route::get('/products-supplied', [\App\Http\Controllers\Supplier\UiMockupController::class, 'productsSupplied'])->name('products-supplied.index');
            Route::get('/delivery-history', [\App\Http\Controllers\Supplier\UiMockupController::class, 'deliveryHistory'])->name('delivery-history.index');
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
            Route::get('/', [\App\Http\Controllers\Customer\UiMockupController::class, 'dashboard'])->name('dashboard');
            Route::get('/home', [\App\Http\Controllers\Customer\UiMockupController::class, 'home'])->name('home');
            
            // Real Customer Controllers
            Route::get('/shop', [\App\Http\Controllers\Customer\CatalogController::class, 'index'])->name('shop');
            
            // Cart
            Route::get('/cart', [\App\Http\Controllers\Customer\CartController::class, 'index'])->name('cart');
            Route::post('/cart', [\App\Http\Controllers\Customer\CartController::class, 'store'])->name('cart.store');
            Route::put('/cart/{cart}', [\App\Http\Controllers\Customer\CartController::class, 'update'])->name('cart.update');
            Route::delete('/cart/{cart}', [\App\Http\Controllers\Customer\CartController::class, 'destroy'])->name('cart.destroy');
            
            // Orders
            Route::get('/orders', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('orders');
            Route::get('/orders/{order}', [\App\Http\Controllers\Customer\OrderController::class, 'show'])->name('orders.show');
            Route::post('/orders', [\App\Http\Controllers\Customer\OrderController::class, 'store'])->name('orders.store');
            
            Route::get('/categories', [\App\Http\Controllers\Customer\UiMockupController::class, 'categories'])->name('categories');
            Route::get('/wishlist', [\App\Http\Controllers\Customer\UiMockupController::class, 'wishlist'])->name('wishlist');
            Route::get('/shipping-address', [\App\Http\Controllers\Customer\UiMockupController::class, 'shippingAddress'])->name('shipping-address');
            Route::get('/payment-methods', [\App\Http\Controllers\Customer\UiMockupController::class, 'paymentMethods'])->name('payment-methods');
            Route::get('/profile', [\App\Http\Controllers\Customer\UiMockupController::class, 'profile'])->name('profile');
        });
});
