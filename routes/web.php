<?php

use App\Livewire\Welcome;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;

Route::get('/', Welcome::class);
Route::get('register', Register::class)->name('auth.register');
Route::get('logout', fn () => auth()->logout())->name('auth.logout');
