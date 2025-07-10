<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UnitTypeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\MarketingMaterialController;
use App\Http\Controllers\AuditLogController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('welcome');
});

// Public Catalog Routes
Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [CatalogController::class, 'index'])->name('index');
    Route::get('/units/{unit}', [CatalogController::class, 'show'])->name('show');
    Route::get('/projects/{project}', [CatalogController::class, 'project'])->name('project');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Projects
    Route::resource('projects', ProjectController::class);

    // Unit Types
    Route::resource('unit-types', UnitTypeController::class);

    // Units
    Route::resource('units', UnitController::class);
    Route::patch('units/{unit}/status', [UnitController::class, 'updateStatus'])->name('units.update-status');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::post('customers/export', [CustomerController::class, 'export'])->name('customers.export');

    // Bookings
    Route::resource('bookings', BookingController::class);
    Route::post('bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('bookings/{booking}/mark-dp-paid', [BookingController::class, 'markDpPaid'])->name('bookings.mark-dp-paid');
    Route::get('bookings/{booking}/contract', [BookingController::class, 'contract'])->name('bookings.contract');

    // Leads
    Route::resource('leads', LeadController::class);
    Route::post('leads/import', [LeadController::class, 'import'])->name('leads.import');

    // Developers
    Route::resource('developers', DeveloperController::class);

    // Locations
    Route::resource('locations', LocationController::class);

    // Campaigns
    Route::resource('campaigns', CampaignController::class);

    // Contracts
    Route::resource('contracts', ContractController::class);

    // Marketing Materials
    Route::resource('marketing-materials', MarketingMaterialController::class);

    // Audit Logs
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('reports/export', [ReportController::class, 'export'])->name('reports.export');
});

require __DIR__ . '/auth.php';
