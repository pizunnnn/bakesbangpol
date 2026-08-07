<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Employee;
use App\Models\Department;
use App\Models\PppkReview;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
  public function __invoke(): View
  {
$statistics = [
      'employees' => Employee::query()->count(),
      'assets' => Asset::query()->count(),
      'available_assets' => Asset::query()->where('status', 'Tersedia')->count(),
      'borrowed_assets' => Asset::query()->where('status', 'Dipinjam')->count(),
      'total_reviews' => PppkReview::query()->count(),
    ];

    $employeesByDepartment = Department::query()
      ->withCount('employees')
      ->orderBy('name')
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
