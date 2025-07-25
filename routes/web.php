<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\DashPhotoController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\AdminPaymentController;

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showlogin')->name('user.login');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/register', 'showregister')->name('user.register');
    Route::post('/register', 'register')->name('register');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/overview', [AdminController::class, 'index'])->name('admin.index');
    Route::get('admin/users', [AdminController::class, 'indexUser'])->name('admin.users.index');
    Route::get('admin/user-create', [AdminController::class, 'showCreateUser'])->name('admin.users.create');
    Route::get('admin/user-edit/{user}', [AdminController::class, 'showEditUser'])->name('admin.users.edit');
    Route::get('admin/user-show/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::post('admin/user-destroy/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::post('admin/user-create/', [AdminController::class, 'storeUser'])->name('admin.users.store');

    Route::get('admin/city', [AdminController::class, 'indexCity'])->name('admin.city.index');
    Route::get('admin/city-create', [AdminController::class, 'showCreateCity'])->name('admin.city.create');
    Route::get('admin/city-edit/{city}', [AdminController::class, 'editCity'])->name('admin.city.edit');
    Route::put('admin/city-edit/{city}', [AdminController::class, 'updateCity'])->name('admin.city.update');
    Route::get('admin/city-show/{city}', [AdminController::class, 'showCity'])->name('admin.city.show');
    Route::delete('admin/city-destroy/{city}', [AdminController::class, 'destroyCity'])->name('admin.city.destroy');
    Route::post('admin/city-create', [AdminController::class, 'storeCity'])->name('admin.city.store');

    Route::get('admin/specialty', [AdminController::class, 'indexSpecialty'])->name('admin.specialties.index');
    Route::get('admin/specialty-create', [AdminController::class, 'showCreateSpecialty'])->name('admin.specialties.create');
    Route::get('admin/specialty-edit/{id}', [AdminController::class, 'showEditSpecialty'])->name('admin.specialties.edit');
    Route::put('admin/specialty-edit/{id}', [AdminController::class, 'updateSpecialty'])->name('admin.specialties.update');
    Route::get('admin/specialty-show/{id}', [AdminController::class, 'showSpecialty'])->name('admin.specialties.show');
    Route::post('admin/specialty-destroy/{id}', [AdminController::class, 'destroySpecialty'])->name('admin.specialties.destroy');
    Route::patch('admin/specialty-toggle-status/{id}', [AdminController::class, 'toggleStatusSpecialty'])->name('admin.specialties.toggle-status');
    Route::post('admin/specialty-create', [AdminController::class, 'storeSpecialty'])->name('admin.specialties.store');

});
Route::middleware(['auth', 'role:photographer'])->group(function () {
    Route::get('/photographer/overview', [DashPhotoController::class, 'index'])->name('photographer.overview');
    Route::get('/photographer/profile', [UserController::class, 'showProfile'])->name('photographer.profile');
    Route::post('/photographer/profile', [UserController::class, 'update'])->name('photographer.profile.update');
    Route::get('/photographer/package', [PackageController::class, 'index'])->name('photographer.packages.index');
    Route::put('/photographer/package', [PackageController::class, 'update'])->name('photographer.packages.update');
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

    Route::get('/photographer/bookings', [BookingController::class, 'index'])->name('photographer.bookings.index');
    Route::patch('/photographer/bookings-confirm/{id}', [BookingController::class, 'confirm'])->name('photographer.booking.confirm');
    Route::patch('/photographer/bookings-complete/{id}', [BookingController::class, 'complete'])->name('photographer.booking.complete');
        Route::patch('/photographer/bookings-reject/{id}', [BookingController::class, 'reject'])->name('photographer.booking.reject');

    Route::get('/photographer/packages', function () {
        return view('photographer.packages');
    })->name('photographer.packages');
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
    Route::get('/customer/photographers', [CostumerController::class, 'showPhotographers'])->name('customer.photographers');

    Route::get('/customer/payment/{booking}', [PaymentController::class, 'create'])->name('customer.payment');
    Route::post('/customer/payment/{booking}', [PaymentController::class, 'store'])->name('customer.payment.store');
    Route::get('/customer/payment/detail/{payment}', [PaymentController::class, 'show'])->name('customer.payment.show');
    Route::post('/customer/payment/detail/{payment}', [PaymentController::class, 'uploadProof'])->name('customer.payment.proof');
    Route::post('/customer/payment/detail/{payment}/cancel', [PaymentController::class, 'cancel'])->name('customer.payment.cancel');
    Route::get('/customer/payment', [PaymentController::class, 'index'])->name('customer.payment.index');
    Route::get('/customer/payment/pay/{payment}', [PaymentController::class, 'showPay'])->name('customer.payment.pay');
    Route::post('/customer/payment/upload-proof/{payment}', [PaymentController::class, 'uploadProof'])->name('customer.payment.upload.store');
    Route::post('/customer/payment-process/{payment}', [PaymentController::class, 'pay'])->name('customer.payment.process');

    Route::get('/customer/bookings/{package}', [BookingController::class, 'create'])->name('customer.bookings');
    Route::post('/customer/bookings/{package}', [BookingController::class, 'store'])->name('customer.bookings.store');
    Route::get('/customer/bookings', [BookingController::class, 'index'])->name('customer.bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/customer/booking-pay/{booking}', [BookingController::class, 'payRoute'])->name('customer.booking.pay');

    Route::get('/customer/profile', [UserController::class, 'showProfile'])->name('customer.profile');
    Route::post('/customer/profile', [UserController::class, 'update'])->name('customer.profile.update');



});
Route::get('/', [CostumerController::class, 'index']);

