<?php

use App\Livewire\Welcome;
use App\Livewire\Auth\{Login, Register, Password};
use Illuminate\Support\Facades\Route;

Route::get('login', Login::class)->name('login');
Route::get('register', Register::class)->name('auth.register');
Route::get('logout', fn () => auth()->logout())->name('auth.logout');
Route::get('password/recovery', Password\Recovery::class)->name('auth.password.recovery');

Route::middleware(['auth'])->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');
});
