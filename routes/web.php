<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VehicleInventoryController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');



});


Route::resource('vendors', VendorController::class)->middleware('auth');

Route::resource('vehicles', VehicleInventoryController::class)->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
});

Route::middleware('auth')->group(function () {
    Route::get('billings', [BillingController::class, 'index'])->name('billings.index');
});

Route::middleware('auth')->group(function () {
    Route::get('reports/admin', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/vendor', [ReportController::class, 'vendor'])->name('reports.vendor');
    Route::post('reports/store', [ReportController::class, 'store'])->name('reports.store');
});

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals.index');
    Route::get('/proposals/admin', [ProposalController::class, 'admin'])->name('proposals.admin');
    Route::delete('/proposals/{id}/edit', [ProposalController::class, 'edit'])->name('proposals.edit');
    Route::post('/proposals', [ProposalController::class, 'store'])->name('proposals.store');
    Route::delete('/proposals/{id}', [ProposalController::class, 'destroy'])->name('proposals.destroy');
    Route::get('/proposals/approved', [ProposalController::class, 'approved'])->name('proposals.approved');
    Route::get('/proposals/preview/{id}', [ProposalController::class, 'preview'])->name('proposals.preview');
    Route::post('/proposals/{id}/approve', [ProposalController::class, 'approve'])->name('proposals.approve');
    Route::post('/proposals/{id}/decline', [ProposalController::class, 'decline'])->name('proposals.decline');

    Route::get('/proposals/generate', [ProposalController::class, 'generateCustomContract'])->name('proposals.generate');
});

Route::middleware('auth')->group(function () {
    Route::get('compliance', [ComplianceController::class, 'index'])->name('compliance.index');
});




require __DIR__ . '/auth.php';
