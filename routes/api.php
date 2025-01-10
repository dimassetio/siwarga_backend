<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RtController;
use App\Http\Controllers\Api\RwController;
use App\Http\Controllers\Api\WargaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
  Route::post('register', 'register');
  Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
  Route::resource('rw', RwController::class);
  Route::resource('rt', RtController::class);
  Route::resource('warga', WargaController::class);
  Route::post('warga-save', [WargaController::class, 'save'])->name('warga.save');
  Route::post('warga-change-status/{id}', [WargaController::class, 'changeStatus'])->name('warga.changeStatus');
  Route::get('warga-summary', [WargaController::class, 'summary']);
  Route::get('warga-summary/{rw_id}', [WargaController::class, 'summary_rw']);
});

Route::get('download-laporan', [WargaController::class, 'downloadPdf']);
