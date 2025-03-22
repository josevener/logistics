<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MarketPlaceAdminController;
use App\Http\Controllers\MarketPlaceVendorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\VehicleInventoryController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::get('/profile/{profile}', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/photo', [ProfileController::class, 'update_profile'])->name('profile.update_profile');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Purchase Orders Routes (Admin and Vendor)
Route::middleware('auth')->group(function () {
    // Admin
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase_orders.index');
    Route::get('/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase_orders.create');
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase_orders.store');
    Route::put('/purchase-orders/{purchaseOrder}/update-status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase_orders.updateStatus');

    // Vendor
    Route::get('/vendor/purchase-orders', [PurchaseOrderController::class, 'vendorIndex'])->name('purchase_orders.vendor.index');
    Route::get('/vendor/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase_orders.vendor.show');
    Route::put('/vendor/purchase-orders/{purchaseOrder}/status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase_orders.vendor.updateStatus');
});

// Vendor and Vehicle Inventory Routes
Route::middleware('auth')->group(function () {
    Route::resource('vendors', VendorController::class);
    Route::resource('vehicles', VehicleInventoryController::class);
});

// Notification Routes
Route::middleware('auth')->group(function () {
    Route::resource('notifications', NotificationController::class)
        ->except(['edit', 'create']);
});

// Marketplace Routes
Route::prefix('marketplace')->middleware('auth')->group(function () {
    // Admin Routes
    Route::get('/admin/store', [MarketPlaceAdminController::class, 'store'])->name('marketplace.admin.store');
    Route::get('/admin/cart', [MarketplaceAdminController::class, 'cart'])->name('marketplace.admin.cart');
    Route::post('/admin/cart/buy', [MarketPlaceAdminController::class, 'buyNow'])->name('marketplace.admin.cart.buy');
    Route::post('/admin/cart/add', [MarketplaceAdminController::class, 'addToCart'])->name('marketplace.admin.cart.add');
    Route::post('/admin/cart/remove', [MarketPlaceAdminController::class, 'removeFromCart'])->name('marketplace.admin.cart.remove');
    Route::post('/admin/cart/checkout', [MarketplaceAdminController::class, 'checkout'])->name('marketplace.admin.cart.checkout');
    Route::post('/admin/cart/remove-selected', [MarketPlaceAdminController::class, 'removeSelected'])->name('marketplace.admin.cart.remove-selected');
    Route::post('/admin/cart/update', [MarketplaceAdminController::class, 'updateQuantity'])->name('marketplace.admin.cart.update');

    // Vendor Routes
    Route::get('/vendor', [MarketPlaceVendorController::class, 'index'])->name('marketplace.vendor.index');
    Route::post('/vendor/products', [MarketplaceVendorController::class, 'store'])->name('marketplace.vendor.products.store');
    Route::put('/vendor/products/{product}', [MarketplaceVendorController::class, 'update'])->name('marketplace.vendor.products.update');
    Route::delete('/vendor/products/{product}', [MarketplaceVendorController::class, 'destroy'])->name('marketplace.vendor.products.destroy');
});

// Maintenance Routes
Route::middleware('auth')->group(function () {
    Route::get('maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::post('maintenance/store', [MaintenanceController::class, 'store'])->name('maintenance.store');
});

// Billing Routes
Route::middleware('auth')->group(function () {
    Route::get('billings', [BillingController::class, 'index'])->name('billings.index');
});

// Reports Routes
Route::middleware('auth')->group(function () {
    Route::get('reports/admin', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/vendor', [ReportController::class, 'vendor'])->name('reports.vendor');
    Route::post('reports/store', [ReportController::class, 'store'])->name('reports.store');
});

// Contract Routes
Route::middleware('auth')->group(function () {
    Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/admin', [ContractController::class, 'admin'])->name('contracts.admin');

    // Upload routes
    Route::get('/contracts/upload', [ContractController::class, 'uploadForm'])->name('contracts.upload');
    Route::post('/contracts/upload', [ContractController::class, 'store'])->name('contracts.store');

    // Admin-specific actions
    Route::post('/contracts/{id}/approve', [ContractController::class, 'approve'])->name('contracts.approve');
    Route::post('/contracts/{id}/decline', [ContractController::class, 'decline'])->name('contracts.decline');
    Route::get('/contracts/preview/{id}', [ContractController::class, 'preview'])->name('contracts.preview');
});

// Proposals Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals.index');
    Route::get('/proposals/admin', [ProposalController::class, 'admin'])->name('proposals.admin');
    Route::delete('/proposals/{id}/edit', [ProposalController::class, 'edit'])->name('proposals.edit');
    Route::get('/proposals/{id}', [ProposalController::class, 'show'])->name('proposals.show');
    Route::post('/proposals', [ProposalController::class, 'store'])->name('proposals.store');
    Route::delete('/proposals/{id}', [ProposalController::class, 'destroy'])->name('proposals.destroy');
    Route::get('/proposals/approved', [ProposalController::class, 'approved'])->name('proposals.approved');
    Route::get('/proposals/preview/{id}', [ProposalController::class, 'preview'])->name('proposals.preview');
    Route::post('/proposals/{id}/approve', [ProposalController::class, 'approve'])->name('proposals.approve');
    Route::post('/proposals/{id}/decline', [ProposalController::class, 'decline'])->name('proposals.decline');
    Route::get('/proposals/generate', [ProposalController::class, 'generateCustomContract'])->name('proposals.generate');
});

// Compliance Routes
Route::middleware('auth')->group(function () {
    Route::get('compliance', [ComplianceController::class, 'index'])->name('compliance.index');
});

require __DIR__ . '/auth.php';
