<?php

use App\Http\Controllers\InvoiceController;
use App\Livewire\Auth\Login;
use App\Livewire\Categories\CategoryIndex;
use App\Livewire\Customers\CustomerDetail;
use App\Livewire\Customers\CustomerIndex;
use App\Livewire\Dashboard\DashboardIndex;
use App\Livewire\PaymentMethods\PaymentMethodIndex;
use App\Livewire\Payments\PaymentIndex;
use App\Livewire\Production\ProductionBoard;
use App\Livewire\Products\ProductIndex;
use App\Livewire\Reports\ReportIndex;
use App\Livewire\Settings\CompanyProfileSettings;
use App\Livewire\Transactions\CreateTransaction;
use App\Livewire\Transactions\EditTransaction;
use App\Livewire\Transactions\TransactionDetail;
use App\Livewire\Transactions\TransactionIndex;
use App\Livewire\Units\UnitIndex;
use App\Livewire\Users\UserIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

// Authenticated Application Routes
Route::middleware(['auth', 'active'])->group(function () {

    // Logout Action
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');

    // Dashboard
    Route::get('/', DashboardIndex::class)->name('dashboard');

    // Transactions Workflow
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', TransactionIndex::class)->name('index');
        Route::get('/create', CreateTransaction::class)->name('create');
        Route::get('/{transaction}', TransactionDetail::class)->name('detail');
        Route::get('/{transaction}/edit', EditTransaction::class)->name('edit');
    });

    // Production Monitoring (Kanban)
    Route::get('/production', ProductionBoard::class)->name('production.board');

    // Payments Registry
    Route::get('/payments', PaymentIndex::class)->name('payments.index');

    // Invoices & Printing
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/{transaction}/preview', [InvoiceController::class, 'preview'])->name('preview');
        Route::get('/{transaction}/print', [InvoiceController::class, 'print'])->name('print');
        Route::get('/{transaction}/download', [InvoiceController::class, 'download'])->name('download');
    });

    // Master Data: Pelanggan
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', CustomerIndex::class)->name('index');
        Route::get('/{customer}', CustomerDetail::class)->name('detail');
    });

    // Master Data: Katalog Produk & Jasa
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', ProductIndex::class)->name('index');
    });

    // Administrator Only Routes
    Route::middleware('admin')->group(function () {
        // Master Data: Kategori Produk & Jasa
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', CategoryIndex::class)->name('index');
        });

        // Master Data: Satuan Ukuran
        Route::prefix('units')->name('units.')->group(function () {
            Route::get('/', UnitIndex::class)->name('index');
        });

        // Master Data: Rekening & Metode Pembayaran
        Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
            Route::get('/', PaymentMethodIndex::class)->name('index');
        });

        // Reports & Analytics
        Route::get('/reports', ReportIndex::class)->name('reports.index');

        // Staff & Users Management
        Route::get('/users', UserIndex::class)->name('users.index');

        // Company Profile Settings
        Route::get('/settings/company', CompanyProfileSettings::class)->name('settings.company');
    });
});
