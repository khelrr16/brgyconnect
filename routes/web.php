<?php

use App\Http\Controllers\Admin\AccountVerificationController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\BlotterRecordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/announcements', [PostController::class, 'announcements']) ->name('announcements.index'); 
Route::get('/posts/{post:slug}', [PostController::class, 'show']) ->name('posts.show');

Route::view('/contacts', 'contacts')->name('contacts');

Route::middleware(['auth', 'verified.user'])->group(function () {
    
});

Route::middleware(['auth'])->group(function () {
    
    Route::middleware('role:super-admin')->prefix('admin')->name('admin.')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::post('/users/password/reset', [UserManagementController::class, 'resetPassword'])->name('users.password.reset');
        Route::patch('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/roles', [UserManagementController::class, 'updateRoles'])->name('users.roles.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

        Route::post('/verifications/{verification}/search-residents', [AccountVerificationController::class, 'searchResidents'])->name('verifications.search-residents');
        Route::patch('/verifications/{verification}/approve', [AccountVerificationController::class, 'approve'])->name('verifications.approve');
        Route::patch('/verifications/{verification}/reject', [AccountVerificationController::class, 'reject'])->name('verifications.reject');

        Route::prefix('posts')->name('posts.')->group(function () {
            Route::get('/', [PostController::class, 'index'])->name('index');
            Route::get('/create', [PostController::class, 'create'])->name('create');
            Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
            Route::post('/create', [PostController::class, 'store'])->name('store');
            Route::patch('/{post}', [PostController::class, 'update'])->name('update');
            Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('residents')->name('residents.')->group(function () {
        Route::get('/', [ResidentController::class, 'index'])->name('index');
        Route::get('/create', [ResidentController::class, 'create'])->name('create');
        Route::get('/{resident}', [ResidentController::class, 'show'])->name('show');
        Route::get('/{resident}/edit', [ResidentController::class, 'edit'])->name('edit');
        Route::patch('/{resident}', [ResidentController::class, 'update'])->name('update');
    });

   

    Route::middleware('role:super-admin|blotter-officer')->prefix('blotters')->name('blotters.')->group(function () {
        Route::view('/', 'blotters.index')->name('index');
        Route::view('/create', 'blotters.create')->name('create');
        Route::get('/{blotter}', [BlotterRecordController::class, 'show'])->name('show');
        Route::get('/{blotter}/edit', [BlotterRecordController::class, 'edit'])->name('edit');
        Route::get('/{blotter}/print', [BlotterRecordController::class, 'print'])->name('print');
        Route::get('/{blotter}/hearing/{hearing}/print', [BlotterRecordController::class, 'printHearing'])->name('hearings.print');
        Route::get('/{blotter}/notice', [BlotterRecordController::class, 'notice'])->name('notice');
        Route::patch('/{blotter}/status',[BlotterRecordController::class, 'updateStatus'])->name('status.update');
    });
});

Route::middleware('auth')->group(function () {

    // Account Verification routes
    Route::get('/verification', [AccountVerificationController::class, 'create'])->name('verifications.create');
    Route::get('/verification/status', [AccountVerificationController::class, 'status'])->name('verifications.status');
    Route::post('/verification', [AccountVerificationController::class, 'store'])->name('verifications.store');

    Route::middleware('role:super-admin')->group(function () {
        Route::get('/verifications', [AccountVerificationController::class, 'index'])->name('admin.verifications.index');
        Route::get('/verifications/{verification}', [AccountVerificationController::class, 'show'])->name('admin.verifications.show');
    });
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
