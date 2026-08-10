<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PppkReviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function (): void {
  Route::get('/dashboard', DashboardController::class)->name('dashboard');

  Route::middleware('role:Administrator|HR / Kepegawaian')->group(function (): void {
Route::resource('employees', EmployeeController::class)->except(['show']);
    Route::resource('assets', AssetController::class)->except(['show']);
    Route::resource('catalog', CatalogController::class)->except(['show']);
    Route::get('reviews', [PppkReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/period', [PppkReviewController::class, 'storePeriod'])->name('reviews.period.store');
    Route::post('reviews/kegiatan', [PppkReviewController::class, 'storeKegiatan'])->name('reviews.kegiatan.store');
    Route::get('reviews/print', [PppkReviewController::class, 'print'])->name('reviews.print');
    Route::delete('reviews/{review}', [PppkReviewController::class, 'destroy'])->name('reviews.destroy');
  });
});
