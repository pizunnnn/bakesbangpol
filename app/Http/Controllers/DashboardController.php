<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetMaintenance;
use App\Models\VehicleRepair;
use App\Models\Employee;
use App\Models\Department;
use App\Models\PppkReview;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
  public function __invoke(): View
  {
    $cutoffDate = Carbon::now()->subYears(10)->format('Y-m-d');
    $cutoffYear = (int) Carbon::now()->subYears(10)->year;

    $statistics = [
      'employees' => Employee::query()->count(),
      'departments' => Department::query()->count(),
      'total_assets' => Asset::query()->count(),
      'active_assets' => Asset::query()->whereIn('status', ['Aktif', 'Tersedia', 'Dipinjam', 'Disetujui'])->count(),
      'in_repair_assets' => Asset::query()->where('status', 'Dalam Perbaikan')->count(),
      'damaged_assets' => Asset::query()->whereIn('status', ['Rusak', 'RB', 'RR', 'Rusak Berat', 'Rusak Ringan'])->count(),
      'aged_assets' => Asset::query()->where(function ($q) use ($cutoffDate, $cutoffYear) {
        $q->where('purchase_date', '<=', $cutoffDate)
          ->orWhere('tahun_perolehan', '<=', $cutoffYear);
      })->count(),
      'disposal_assets' => Asset::query()->where('status', 'Dapat Dihapus')->orWhere(function ($q) use ($cutoffDate, $cutoffYear) {
        $q->where('purchase_date', '<=', $cutoffDate)
          ->orWhere('tahun_perolehan', '<=', $cutoffYear);
      })->count(),
      'total_maintenances' => AssetMaintenance::query()->count(),
      'total_maintenance_cost' => (float) AssetMaintenance::query()->sum('cost') + (float) VehicleRepair::query()->sum('cost'),
      'total_vehicles' => Asset::query()->whereHas('categoryRelation', function ($q) {
        $q->where('name', 'like', '%kendaraan%');
      })->orWhere('nama_barang', 'like', '%mobil%')
        ->orWhere('nama_barang', 'like', '%motor%')
        ->orWhere('nama_barang', 'like', '%bus%')
        ->orWhere('nama_barang', 'like', '%hiace%')
        ->count(),
      'vehicles_in_repair' => VehicleRepair::query()->whereIn('status', ['Diajukan', 'Dalam Perbaikan'])->count(),
      'promotions_eligible_count' => Employee::where('employment_status', '!=', 'inactive')->with('rankHistories')->get()->filter(fn($e) => $e->is_eligible_kenaikan_pangkat || ($e->tanggal_kenaikan_pangkat_berikutnya && $e->tanggal_kenaikan_pangkat_berikutnya->format('Y-m') === Carbon::now()->format('Y-m')))->count(),
    ];

    $employeesByDepartment = Department::query()
      ->withCount('employees')
      ->orderByDesc('employees_count')
      ->get();

    $assetsByCategory = AssetCategory::query()
      ->withCount('assets')
      ->orderBy('name')
      ->get();

    $recentEmployees = Employee::query()
      ->with(['department', 'position'])
      ->latest()
      ->take(5)
      ->get();

    $recentAssets = Asset::query()
      ->with('categoryRelation')
      ->latest()
      ->take(5)
      ->get();

    return view('dashboard.index', compact(
      'statistics',
      'employeesByDepartment',
      'assetsByCategory',
      'recentEmployees',
      'recentAssets',
    ));
  }
}
