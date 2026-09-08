<?php

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\AuthController;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Support\Facades\Route;

// Welcome / Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// Authenticated Admin & Staff Portal Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard with Multi-Period Tracking for Admin & POS Drawer for Cashier
    Route::get('/dashboard', function () {
        $today = today();
        $startOfWeek = now()->startOfWeek();
        $startOfMonth = now()->startOfMonth();
        $startOfYear = now()->startOfYear();

        // Multi-period revenue tracking for Admin
        $metrics = [
            'today_revenue' => Payment::whereDate('payment_date', $today)->sum('amount'),
            'today_invoices' => Invoice::whereDate('created_at', $today)->count(),
            'weekly_revenue' => Payment::whereBetween('payment_date', [$startOfWeek, now()])->sum('amount'),
            'weekly_invoices' => Invoice::whereBetween('created_at', [$startOfWeek, now()])->count(),
            'monthly_revenue' => Payment::whereBetween('payment_date', [$startOfMonth, now()])->sum('amount'),
            'monthly_invoices' => Invoice::whereBetween('created_at', [$startOfMonth, now()])->count(),
            'yearly_revenue' => Payment::whereBetween('payment_date', [$startOfYear, now()])->sum('amount'),
            'outstanding_debt' => Invoice::whereIn('status', ['unpaid', 'partial'])->get()->sum(fn($i) => $i->total_amount - $i->paid_amount),
            'total_patients' => Patient::count(),
            'low_stock_items' => Item::where('category', 'Medication')->where('stock_quantity', '<', 50)->count(),
        ];

        // Multi-Period Payment Channel Breakdowns (Daily / Weekly / Monthly)
        $channelBreakdown = [
            'daily' => [
                'cash' => Payment::where('payment_method', 'cash')->whereDate('payment_date', $today)->sum('amount'),
                'card' => Payment::where('payment_method', 'card')->whereDate('payment_date', $today)->sum('amount'),
                'qr' => Payment::where('payment_method', 'qr')->whereDate('payment_date', $today)->sum('amount'),
                'transfer' => Payment::where('payment_method', 'transfer')->whereDate('payment_date', $today)->sum('amount'),
                'panel' => Payment::where('payment_method', 'panel')->whereDate('payment_date', $today)->sum('amount'),
            ],
            'weekly' => [
                'cash' => Payment::where('payment_method', 'cash')->whereBetween('payment_date', [$startOfWeek, now()])->sum('amount'),
                'card' => Payment::where('payment_method', 'card')->whereBetween('payment_date', [$startOfWeek, now()])->sum('amount'),
                'qr' => Payment::where('payment_method', 'qr')->whereBetween('payment_date', [$startOfWeek, now()])->sum('amount'),
                'transfer' => Payment::where('payment_method', 'transfer')->whereBetween('payment_date', [$startOfWeek, now()])->sum('amount'),
                'panel' => Payment::where('payment_method', 'panel')->whereBetween('payment_date', [$startOfWeek, now()])->sum('amount'),
            ],
            'monthly' => [
                'cash' => Payment::where('payment_method', 'cash')->whereBetween('payment_date', [$startOfMonth, now()])->sum('amount'),
                'card' => Payment::where('payment_method', 'card')->whereBetween('payment_date', [$startOfMonth, now()])->sum('amount'),
                'qr' => Payment::where('payment_method', 'qr')->whereBetween('payment_date', [$startOfMonth, now()])->sum('amount'),
                'transfer' => Payment::where('payment_method', 'transfer')->whereBetween('payment_date', [$startOfMonth, now()])->sum('amount'),
                'panel' => Payment::where('payment_method', 'panel')->whereBetween('payment_date', [$startOfMonth, now()])->sum('amount'),
            ],
        ];

        // Recent Invoices & Pending Discharge Queue
        $recentInvoices = Invoice::with(['patient', 'creator'])->latest()->take(6)->get();
        $pendingDischarge = Invoice::with('patient')->whereIn('status', ['unpaid', 'partial'])->latest()->take(5)->get();

        // Backward compatibility for existing views
        $stats = [
            'today_invoices' => $metrics['today_invoices'],
            'today_collections' => $metrics['today_revenue'],
            'outstanding' => $metrics['outstanding_debt'],
            'total_patients' => $metrics['total_patients'],
        ];

        return view('dashboard', compact('metrics', 'channelBreakdown', 'recentInvoices', 'pendingDischarge', 'stats'));
    })->name('dashboard');

    // Module 3 & 4: Invoices & POS Billing
    Route::prefix('admin/invoices')->name('admin.invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::post('/{invoice}/settle', [InvoiceController::class, 'settlePayment'])->name('settle');
        Route::post('/{invoice}/void', [InvoiceController::class, 'voidInvoice'])->name('void');
    });

    // Module 1: Patient Management
    Route::resource('admin/patients', PatientController::class)->names('admin.patients');

    // Module 2: Treatment & Medication Catalog (Item Master)
    Route::resource('admin/items', ItemController::class)->names('admin.items');

    // Module 5: Reporting, Cash Drawer & Bank Reconciliation
    Route::prefix('admin/reports')->name('admin.reports.')->group(function () {
        Route::get('/cash-drawer', [ReportController::class, 'dailyCashDrawer'])->name('cash-drawer');
        Route::get('/bank-reconciliation', [ReportController::class, 'bankReconciliation'])->name('bank-recon');
        Route::post('/bank-reconciliation/{payment}/toggle', [ReportController::class, 'toggleReconciled'])->name('reconcile-toggle');
        Route::get('/analytics', [ReportController::class, 'revenueAnalytics'])->name('analytics');
    });

    // Module 6 & 7: Governance, Settings & Audit Logs (Admin Only)
    Route::prefix('admin/settings')->name('admin.settings.')->middleware('admin')->group(function () {
        Route::get('/profile', [SettingController::class, 'clinicProfile'])->name('profile');
        Route::put('/profile', [SettingController::class, 'updateClinicProfile'])->name('profile.update');
        Route::get('/templates', [SettingController::class, 'invoiceTemplates'])->name('templates');
        Route::get('/audit-logs', [SettingController::class, 'auditLogs'])->name('audit-logs');
    });

    // Module 8: Staff User & Role Identity Management (Admin Only)
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::post('users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});
