<?php

use App\Http\Controllers\Landing\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/hapus-akun', [LandingController::class, 'hapusAkun'])->name('hapus-akun');
Route::get('/privasi-kebijakan', [LandingController::class, 'privasi'])->name('privasi-kebijakan');
Route::post('/kontak', [LandingController::class, 'storePesan'])->name('landing.kontak');