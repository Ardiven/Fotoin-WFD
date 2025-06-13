<?php

use App\Models\Payment;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Role;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PotofolioController;
use App\Http\Controllers\AdminPaymentController;

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showlogin')->name('user.login');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/register', 'showregister')->name('user.register');
    Route::post('/register', 'register')->name('register');
});
Route::middleware(['auth', 'role:photographer'])->group(function () {
    Route::get('/photographer/overview', function () {return view('photographer.overview');})->name('photographer.overview');
    Route::get('/photographer/profile', [UserController::class, 'showProfile'])->name('photographer.profile');
    Route::post('/photographer/profile', [UserController::class, 'update'])->name('photographer.profile.update');
    Route::get('/photographer/package', [PackageController::class, 'index'])->name('photographer.packages.index');
    Route::post('/photographer/package', [PackageController::class, 'update'])->name('photographer.packages.update');
    Route::get('/photographer/package/create', [PackageController::class, 'create'])->name('photographer.packages.create');
    Route::post('/photographer/package/create', [PackageController::class, 'store'])->name('photographer.packages.store');
    Route::get('/photographer/package/{package}/edit', [PackageController::class, 'edit'])->name('photographer.packages.edit');
    Route::put('/photographer/package/{package}', [PackageController::class, 'update'])->name('photographer.packages.update');
    Route::delete('/photographer/package/{package}', [PackageController::class, 'destroy'])->name('photographer.packages.destroy');

    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('photographer.portfolio.index');
    Route::post('/portfolio', [PortfolioController::class, 'store'])->name('photographer.portfolio.store');
    Route::get('/portfolio/{id}/edit', [PortfolioController::class, 'edit']);
    Route::put('/portfolio/{id}', [PortfolioController::class, 'update'])->name('photographer.portfolio.update');
    Route::delete('/portfolio/{portfolio}', [PortfolioController::class, 'destroy'])->name('photographer.portfolio.destroy');

    Route::get('/photographer/payment', [PaymentController::class, 'index'])->name('photographer.payment.index');
    Route::get('/photographer/payment/detail/{payment}', [PaymentController::class, 'show'])->name('photographer.payment.show');
    Route::patch('/photographer/payment/detail-approve/{payment}', [AdminPaymentController::class, 'approveProof'])->name('photographer.payment.approveproof');
    Route::patch('/photographer/payment/detail-reject/{payment}', [AdminPaymentController::class, 'rejectProof'])->name('photographer.payment.rejectproof');

    Route::get('/photographer/packages', function () {
        return view('photographer.packages');
    })->name('photographer.packages');
    Route::get('/photographer/bookings', function () {
        return view('photographer.bookings');
    })->name('photographer.bookings');
    Route::get('/photographer/chat', function () {
        return view('photographer.chat');
    })->name('photographer.chat');
    Route::get('/photographer/portfolio', function () {
        return view('photographer.portfolio');
    })->name('photographer.portfolio');
    Route::get('/photographer/analytics', function () {
        return view('photographer.analytics');
    })->name('photographer.analytics');
    

});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/index', [CostumerController::class, 'index'])->name('customer.index');
    Route::get('/customer/profile/{photographer}', [CostumerController::class, 'showProfile'])->name('customer.photographers.show');
    Route::get('/customer/profile/showcase/{photographer}', [CostumerController::class, 'showPortfolio'])->name('customer.photographers.showcase');
    Route::get('/customer/chat', function () {
        return view('customer.chat');
    })->name('chat');
    Route::get('/customer/photographers', [CostumerController::class, 'showPhotographers'])->name('customer.photographers');
    Route::get('/customer/payment/{package}', [PaymentController::class, 'create'])->name('customer.payment');
    Route::post('/customer/payment/{package}', [PaymentController::class, 'store'])->name('customer.payment.store');
    Route::get('/customer/payment/detail/{payment}', [PaymentController::class, 'show'])->name('customer.payment.show');
    Route::post('/customer/payment/detail/{payment}', [PaymentController::class, 'uploadProof'])->name('customer.payment.proof');
    Route::post('/customer/payment/detail/{payment}/cancel', [PaymentController::class, 'cancel'])->name('customer.payment.cancel');
    Route::get('/customer/payment', [PaymentController::class, 'index'])->name('customer.payment.index');
    Route::get('/customer/payment/pay/{payment}', [PaymentController::class, 'showPay'])->name('customer.payment.pay');
    Route::post('/customer/payment/upload-proof/{payment}', [PaymentController::class, 'uploadProof'])->name('customer.payment.upload.store');
    Route::post('/customer/payment-process/{payment}', [PaymentController::class, 'pay'])->name('customer.payment.process');
});


Route::get('/', [CostumerController::class, 'index']);

