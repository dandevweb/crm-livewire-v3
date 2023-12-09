<?php

use App\Livewire\Welcome;
use App\Livewire\Auth\{Login, Register, Password};
use Illuminate\Support\Facades\Route;

//region Login Flow
Route::get('login', Login::class)->name('login');
Route::get('register', Register::class)->name('auth.register');
Route::get('logout', fn () => auth()->logout())->name('auth.logout');
Route::get('password/recovery', Password\Recovery::class)->name('password.recovery');
Route::get('password/reset', Password\Reset::class)->name('password.reset');
//endregion

// region Authenticated
Route::middleware(['auth'])->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');

    //region Admin
    Route::prefix('/admin')->middleware('can:be-an-admin')->group(function () {
        Route::get('/dashboard', fn () => 'admin.dashboard')->name('admin.dashboard');
    });

    //endregion
});

//endregion
