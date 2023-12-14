<?php

use App\Enums\Can;
use App\Livewire\{Admin, Welcome};
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
    Route::prefix('/admin')->middleware('can:' . Can::BE_AN_ADMIN->value)->group(function () {
        Route::get('/dashboard', Admin\Dashboard::class)->name('admin.dashboard');

        Route::get('/users', fn () => 'oi')->name('admin.users');
    });

    //endregion
});

//endregion
