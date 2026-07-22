<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\InvoiceController;

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('members', MemberController::class);
    Route::get('/members/{member}/card',[MemberController::class, 'card'])->name('members.card');

    Route::resource('plans', PlanController::class);

    Route::resource('offers', OfferController::class);

    Route::resource('payments', PaymentController::class);
    
    Route::controller(SubscriptionController::class)
        ->prefix('subscriptions')->name('subscriptions.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{subscription}', 'show')->name('show');
            Route::delete('/{subscription}', 'destroy')->name('destroy');
            Route::post('/{subscription}/renew', 'renew')->name('renew');
            Route::post('/{subscription}/freeze', 'freeze')->name('freeze');
            Route::post('/{subscription}/unfreeze', 'unfreeze')->name('unfreeze');
            Route::post('/{subscription}/cancel', 'cancel')->name('cancel');
        });
    Route::prefix('attendances')->name('attendances.')->group(function () {
 
    // شاشة السكانر (الريسبشن)
    Route::get('/scan', [AttendanceController::class, 'scanPage'])->name('scan');
    Route::post('/scan', [AttendanceController::class, 'scan'])->name('scan.store');
 
    // سجل الحضور والانصراف
    Route::get('/', [AttendanceController::class, 'index'])->name('index');
    Route::delete('/{attendance}', [AttendanceController::class, 'destroy'])->name('destroy');
    Route::patch('/{attendance}/force-checkout', [AttendanceController::class, 'forceCheckout'])->name('force-checkout');
        });
    Route::controller(ReportController::class)
        ->prefix('reports')->name('reports.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/members/export/excel', 'exportMembersExcel')->name('members.export.excel');
            Route::get('/members/export/pdf', 'exportMembersPdf')->name('members.export.pdf');
        });
    Route::prefix('settings')
    ->name('settings.')
    ->group(function () {

        Route::get(
            '/',
            [SettingsController::class,'edit']
        )->name('edit');

        Route::put(
            '/',
            [SettingsController::class,'update']
        )->name('update');
    });
    Route::prefix('payments/{payment}/invoice')
    ->name('payments.invoice.')
    ->group(function () {

        Route::get('/print', [InvoiceController::class, 'print'])
            ->name('print');

        Route::get('/pdf', [InvoiceController::class, 'pdf'])
            ->name('pdf');

        Route::get('/download', [InvoiceController::class, 'download'])
            ->name('download');
    });
});

















Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
