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



Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('members', MemberController::class);

    Route::resource('plans', PlanController::class);

    Route::resource('offers', OfferController::class);

    Route::resource('subscriptions', SubscriptionController::class);

    Route::resource('payments', PaymentController::class);

    Route::resource('attendances', AttendanceController::class);

});

















Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
