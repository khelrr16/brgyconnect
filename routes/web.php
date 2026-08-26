<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentController;
use App\Models\BlotterRecord;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/residents', [ResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/create', [ResidentController::class, 'create'])->name('residents.create');
    Route::get('/residents/{resident}', [ResidentController::class, 'show'])->name('residents.show');
    Route::get('/residents/{resident}/edit', [ResidentController::class, 'edit'])->name('residents.edit');
    Route::patch('/residents/{resident}', [ResidentController::class, 'update'])->name('residents.update');

    Route::view('/blotters', 'blotters.index')->name('blotters.index');
    Route::view('/blotters/create', 'blotters.create')->name('blotters.create');
    
    Route::get('/blotters/{blotter}/edit', function (BlotterRecord $blotter) {
        return view('blotters.edit', compact('blotter'));
    })->name('blotters.edit');

    Route::get('/blotters/{blotter}', function (BlotterRecord $blotter) {
        return view('blotters.show', compact('blotter'));
    })->name('blotters.show');

    Route::get('/blotters/{blotter}/print', function (BlotterRecord $blotter) {
        $blotter->load([
            'parties',
            'attachments',
            'hearings',
            'creator',
        ]);

        return view('blotters.print', compact('blotter'));
    })->name('blotters.print');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
