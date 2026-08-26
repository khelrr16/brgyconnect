<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\UserManagementController;
use App\Models\BlotterRecord;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

Route::middleware(['auth', 'role:super-admin'])->group(function () {
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::patch('/admin/users/{user}/roles', [UserManagementController::class, 'updateRoles'])->name('admin.users.roles.update');
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/residents', [ResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/create', [ResidentController::class, 'create'])->name('residents.create');
    Route::get('/residents/{resident}', [ResidentController::class, 'show'])->name('residents.show');
    Route::get('/residents/{resident}/edit', [ResidentController::class, 'edit'])->name('residents.edit');
    Route::patch('/residents/{resident}', [ResidentController::class, 'update'])->name('residents.update');

    Route::view('/blotters', 'blotters.index')->name('blotters.index')->middleware('permission:view-blotter-records');
    Route::view('/blotters/create', 'blotters.create')->name('blotters.create')->middleware('permission:create-blotter-records');

    Route::get('/blotters/{blotter}/edit', function (BlotterRecord $blotter) {
        return view('blotters.edit', compact('blotter'));
    })->name('blotters.edit')->middleware('permission:edit-blotter-records');

    Route::get('/blotters/{blotter}', function (BlotterRecord $blotter) {
        return view('blotters.show', compact('blotter'));
    })->name('blotters.show')->middleware('permission:view-blotter-records');

    Route::get('/blotters/{blotter}/print', function (BlotterRecord $blotter) {
        $blotter->load([
            'parties',
            'attachments',
            'hearings',
            'creator',
        ]);

        return view('blotters.print', compact('blotter'));
    })->name('blotters.print')->middleware('permission:view-blotter-records');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
