<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\AssetMaintenanceController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EmployeeHistoryController;
use App\Http\Controllers\Admin\PppkReviewController;
use App\Http\Controllers\Admin\VehicleRepairController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function (): void {
  Route::get('/dashboard', DashboardController::class)->name('dashboard');

  Route::middleware('role:Administrator|HR / Kepegawaian')->group(function (): void {
    // Export PDF Data Pegawai
    Route::get('employees/export-pdf', [EmployeeController::class, 'exportPdf'])->name('employees.export-pdf');

    // CRUD Pegawai
    Route::resource('employees', EmployeeController::class);

    // Dynamic Unit Kerja
    Route::resource('departments', DepartmentController::class)->except(['create', 'show', 'edit']);

    // Riwayat / History Pegawai & Auto Actions
    Route::post('employees/{employee}/trainings', [EmployeeHistoryController::class, 'storeTraining'])->name('employees.trainings.store');
    Route::delete('employees/trainings/{training}', [EmployeeHistoryController::class, 'destroyTraining'])->name('employees.trainings.destroy');

    Route::post('employees/{employee}/salaries', [EmployeeHistoryController::class, 'storeSalary'])->name('employees.salaries.store');
    Route::post('employees/{employee}/salaries/auto', [EmployeeHistoryController::class, 'autoKgb'])->name('employees.salaries.auto');
    Route::delete('employees/salaries/{salary}', [EmployeeHistoryController::class, 'destroySalary'])->name('employees.salaries.destroy');

    Route::post('employees/{employee}/ranks', [EmployeeHistoryController::class, 'storeRank'])->name('employees.ranks.store');
    Route::post('employees/{employee}/ranks/auto', [EmployeeHistoryController::class, 'autoRank'])->name('employees.ranks.auto');
    Route::delete('employees/ranks/{rank}', [EmployeeHistoryController::class, 'destroyRank'])->name('employees.ranks.destroy');

    Route::post('employees/{employee}/positions', [EmployeeHistoryController::class, 'storePosition'])->name('employees.positions.store');
    Route::delete('employees/positions/{position}', [EmployeeHistoryController::class, 'destroyPosition'])->name('employees.positions.destroy');

    Route::post('employees/{employee}/retirements', [EmployeeHistoryController::class, 'storeRetirement'])->name('employees.retirements.store');
    Route::delete('employees/retirements/{retirement}', [EmployeeHistoryController::class, 'destroyRetirement'])->name('employees.retirements.destroy');

    // MANAJEMEN ASET
    Route::get('assets/deletable', [AssetController::class, 'deletable'])->name('assets.deletable');
    Route::post('assets/{asset}/verify-disposal', [AssetController::class, 'verifyDisposal'])->name('assets.verify-disposal');
    Route::post('assets/import-excel', [AssetController::class, 'importExcel'])->name('assets.import-excel');
    Route::resource('assets', AssetController::class);

    // Pemeliharaan Aset & Upload Nota
    Route::post('assets/{asset}/maintenances', [AssetMaintenanceController::class, 'store'])->name('assets.maintenances.store');
    Route::delete('assets/maintenances/{maintenance}', [AssetMaintenanceController::class, 'destroy'])->name('assets.maintenances.destroy');
    Route::get('assets/maintenances/{maintenance}/receipt', [AssetMaintenanceController::class, 'downloadReceipt'])->name('assets.maintenances.receipt');

    // Perbaikan Kendaraan & Upload Nota
    Route::post('assets/{asset}/repairs', [VehicleRepairController::class, 'store'])->name('assets.repairs.store');
    Route::delete('assets/repairs/{repair}', [VehicleRepairController::class, 'destroy'])->name('assets.repairs.destroy');
    Route::get('assets/repairs/{repair}/receipt', [VehicleRepairController::class, 'downloadReceipt'])->name('assets.repairs.receipt');

    Route::resource('catalog', CatalogController::class)->except(['show']);
    Route::get('reviews', [PppkReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/period', [PppkReviewController::class, 'storePeriod'])->name('reviews.period.store');
    Route::post('reviews/kegiatan', [PppkReviewController::class, 'storeKegiatan'])->name('reviews.kegiatan.store');
    Route::get('reviews/print', [PppkReviewController::class, 'print'])->name('reviews.print');
    Route::delete('reviews/{review}', [PppkReviewController::class, 'destroy'])->name('reviews.destroy');
  });
});
