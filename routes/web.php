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
// The Landing Page is ONLY accessible for guests.
// If a logged-in user visits '/', the 'guest' middleware will redirect them to '/dashboard'.
Route::middleware('guest')->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\CatalogController::class, 'home'])->name('home');
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
            Route::post('/orders/{order}/cancel', [\App\Http\Controllers\Admin\AdminOrderController::class, 'cancel'])->name('orders.cancel');
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
            Route::post('/purchase-requests/{purchaseRequest}/generate-po', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'generatePo'])->name('purchase-requests.generate-po');
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
            Route::get('/', [\App\Http\Controllers\InventoryManager\DashboardController::class, 'index'])->name('dashboard');
            
            Route::get('products', [\App\Http\Controllers\InventoryManager\ProductController::class, 'index'])->name('products.index');
            Route::post('products', [\App\Http\Controllers\InventoryManager\ProductController::class, 'store'])->name('products.store');
            Route::get('products/{product}', [\App\Http\Controllers\InventoryManager\ProductController::class, 'show'])->name('products.show');
            Route::put('products/{product}', [\App\Http\Controllers\InventoryManager\ProductController::class, 'update'])->name('products.update');
            Route::delete('products/{product}', [\App\Http\Controllers\InventoryManager\ProductController::class, 'destroy'])->name('products.destroy');

            Route::get('goods-receipts/create', [\App\Http\Controllers\InventoryManager\GoodsReceiptController::class, 'create'])->name('goods-receipts.create');
            Route::post('goods-receipts', [\App\Http\Controllers\InventoryManager\GoodsReceiptController::class, 'store'])->name('goods-receipts.store');

            Route::get('supply-inventory', [\App\Http\Controllers\InventoryManager\InventoryController::class, 'index'])->name('supply-inventory.index');
            Route::post('supply-inventory/{product}/transaction', [\App\Http\Controllers\InventoryManager\InventoryController::class, 'transaction'])->name('supply-inventory.transaction');

            // Real Supply Chain Controllers (Shared with Admin)
            Route::get('purchase-requests', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'index'])->name('purchase-requests.index');
            Route::get('purchase-requests/create', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'create'])->name('purchase-requests.create');
            Route::post('purchase-requests', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'store'])->name('purchase-requests.store');
            Route::post('purchase-requests/{purchaseRequest}/generate-po', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'generatePo'])->name('purchase-requests.generate-po');
            Route::post('purchase-requests/{purchaseRequest}/reject', [\App\Http\Controllers\Admin\SupplyChain\PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');
            
            Route::get('purchase-orders', [\App\Http\Controllers\Admin\SupplyChain\PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
            Route::get('purchase-orders/{purchaseOrder}', [\App\Http\Controllers\Admin\SupplyChain\PurchaseOrderController::class, 'show'])->name('purchase-orders.show');

            Route::get('reports', [\App\Http\Controllers\InventoryManager\ReportController::class, 'index'])->name('reports.index');
            
            // UI Mockup Routes for IM -> Transitioning to real controllers
            Route::get('stock-movement', [\App\Http\Controllers\InventoryManager\StockMovementController::class, 'index'])->name('stock-movement.index');
            Route::get('low-stock', [\App\Http\Controllers\InventoryManager\ReportController::class, 'index'])->name('low-stock.index');
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
            Route::get('/', [\App\Http\Controllers\Supplier\DashboardController::class, 'index'])->name('dashboard');
            
            // Purchase Requests
            Route::get('/purchase-requests', [\App\Http\Controllers\Supplier\PurchaseRequestController::class, 'index'])->name('purchase-requests.index');
            Route::post('/purchase-requests/{purchaseRequest}/approve', [\App\Http\Controllers\Supplier\PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
            Route::post('/purchase-requests/{purchaseRequest}/reject', [\App\Http\Controllers\Supplier\PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');

            Route::get('/purchase-orders', [\App\Http\Controllers\Supplier\PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
            Route::post('/purchase-orders/{purchaseOrder}/status', [\App\Http\Controllers\Supplier\PurchaseOrderController::class, 'updateStatus'])->name('purchase-orders.update-status');
            
            // Products/Catalog
            Route::get('/inventory', [\App\Http\Controllers\Supplier\ProductController::class, 'index'])->name('inventory.index');
            Route::post('/inventory', [\App\Http\Controllers\Supplier\ProductController::class, 'store'])->name('inventory.store');
            Route::put('/inventory/{supplierProduct}', [\App\Http\Controllers\Supplier\ProductController::class, 'update'])->name('inventory.update');
            Route::delete('/inventory/{supplierProduct}', [\App\Http\Controllers\Supplier\ProductController::class, 'destroy'])->name('inventory.destroy');
            
            // Deliveries
            Route::get('/deliveries', [\App\Http\Controllers\Supplier\DeliveryController::class, 'index'])->name('deliveries.index');
            Route::post('/deliveries/{purchaseOrder}/ship', [\App\Http\Controllers\Supplier\DeliveryController::class, 'ship'])->name('deliveries.ship');
            
            // Invoices/Billing
            Route::get('/invoices', [\App\Http\Controllers\Supplier\InvoiceController::class, 'index'])->name('invoices.index');
            Route::post('/invoices/{purchaseOrder}/mark-paid', [\App\Http\Controllers\Supplier\InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
            Route::get('/invoices/{purchaseOrder}/download', [\App\Http\Controllers\Supplier\InvoiceController::class, 'downloadPdf'])->name('invoices.download');
            
            // Notifications
            Route::get('/notifications', [\App\Http\Controllers\Supplier\NotificationController::class, 'index'])->name('notifications.index');
            Route::post('/notifications/mark-read', [\App\Http\Controllers\Supplier\NotificationController::class, 'markAllRead'])->name('notifications.mark-read');
            
            // Reports
            Route::get('/reports', [\App\Http\Controllers\Supplier\ReportController::class, 'index'])->name('reports.index');
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
            // Catalog routes
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
