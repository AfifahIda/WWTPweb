<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WWTPController;

// Halaman utama dashboard (Blade view)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard/stream', [DashboardController::class, 'stream']);


Route::get('/wwtp', [WWTPController::class, 'index'])->name('wwtp.index');

// Realtime streaming data (SSE)
Route::get('/wwtp/stream', [WWTPController::class, 'streamData'])->name('wwtp.stream');

// Ambil histori data (default 8 jam terakhir)
Route::get('/wwtp/history', [WWTPController::class, 'getHistoricalData'])->name('wwtp.history');

// Ambil status sistem
Route::get('/wwtp/status', [WWTPController::class, 'getSystemStatus'])->name('wwtp.status');

// Endpoint simple (ambil snapshot data terbaru)
Route::get('/wwtp/simple', [WWTPController::class, 'getSimpleData'])->name('wwtp.simple');

// Force isi chart manual (debugging)
Route::post('/wwtp/force/{chartId}', [WWTPController::class, 'forceChart'])->name('wwtp.force');

// Untuk tombol refresh
Route::post('/dashboard/refresh', [DashboardController::class, 'refresh']);