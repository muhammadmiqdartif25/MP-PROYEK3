<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome.index');
})->name('welcome.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login.index');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home.index');
    })->name('home.index');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('can:manage-areas')->group(function () {
        Route::get('/area', [AreaController::class, 'index'])->name('area.index');
    });

    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');

    Route::middleware('can:submit-report')->group(function () {
        Route::get('/laporan/buat', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
    });

    Route::get('/laporan/{report}', [ReportController::class, 'show'])->name('reports.show')->whereNumber('report');
    Route::get('/laporan/{report}/bukti', [ReportController::class, 'evidence'])->name('reports.evidence')->whereNumber('report');
    Route::get('/laporan/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit')->whereNumber('report');
    Route::put('/laporan/{report}', [ReportController::class, 'update'])->name('reports.update')->whereNumber('report');
});
