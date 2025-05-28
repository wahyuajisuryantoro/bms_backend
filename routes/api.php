<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RegionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ListMobilController;
use App\Http\Controllers\Api\DetailMobilController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
     ->name('verification.verify');

Route::get('/email/verify-token/{token}', [AuthController::class, 'verifyToken'])
     ->name('verification.verify-token');

Route::post('/email/resend', [AuthController::class, 'resendVerification']);

Route::post('/email/check-status', [AuthController::class, 'checkEmailVerificationStatus']);

Route::post('/email/check-registration', [AuthController::class, 'checkRegistrationStatus']);


Route::middleware('auth:sanctum')->group(function () {
     Route::post('logout', [AuthController::class, 'logout']);
     Route::put('update-password', [AuthController::class, 'updatePassword']);

     Route::get('/mobil-dashboard', [HomeController::class, 'getDataMobilDashboard']);
     Route::get('/merk-mobil', [HomeController::class, 'getAllDataMerkMobil']);
     Route::get('/transmisi', [HomeController::class, 'getAllDataTransmisi']);

     Route::get('/mobil-all', [ListMobilController::class, 'getAllDataMobil']);
     Route::get('/mobil-detail/tenor-options', [DetailMobilController::class, 'getTenorOptions']);
     Route::get('/mobil-detail/dp-options', [DetailMobilController::class, 'getDpOptions']);
     Route::get('/favorites/all', [FavoriteController::class, 'getAllDataFavorite']);
     Route::delete('/favorites/clear', [FavoriteController::class, 'clearAllFavorites']);
     Route::get('/profile-detail', [ProfileController::class, 'show']);
     Route::put('/profile-detail-update', [ProfileController::class, 'update']);
     Route::get('provinces', [RegionController::class, 'getProvinces']);

     Route::post('/mobil-detail/{id}/simulasi-kredit', [DetailMobilController::class, 'simulasiKredit']);
     Route::get('/mobil-detail/{id}', [DetailMobilController::class, 'getDetailMobil']);
     Route::put('/mobil-detail/{mobil_id}/toggle-favorite', [DetailMobilController::class, 'toggleFavoriteStatus']);
     Route::delete('/favorites/remove/{favorite_id}', [FavoriteController::class, 'removeFavorite']);


     Route::get('regencies/{provinceId}', [RegionController::class, 'getRegencies']);
     Route::get('districts/{regencyId}', [RegionController::class, 'getDistricts']);
     Route::get('villages/{districtId}', [RegionController::class, 'getVillages']);


});