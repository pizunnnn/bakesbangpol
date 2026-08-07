<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeController extends Controller
{
public function index(): View
  {
    $search = request('search');
    $statusPegawai = request('status_pegawai');
    $statusKepegawaian = request('status_kepegawaian');

    $employees = Employee::with(['department', 'position', 'user'])
      ->when($search, function ($query) use ($search) {
        $query->where(function ($q) use ($search) {
          $q->where('full_name', 'like', "%{$search}%")
            ->orWhere('employee_number', 'like', "%{$search}%")
            ->orWhere('unit_kerja', 'like', "%{$search}%")
            ->orWhere('status_pegawai', 'like', "%{$search}%")
            ->orWhereHas('position', function ($p) use ($search) {
              $p->where('name', 'like', "%{$search}%");
            });
        });
      })
      ->when($statusPegawai, function ($query) use ($statusPegawai) {
        $query->where('status_pegawai', $statusPegawai);
      })
      ->when($statusKepegawaian, function ($query) use ($statusKepegawaian) {
        $query->where('employment_status', $statusKepegawaian);
      })
      ->latest()
      ->paginate(10)
      ->withQueryString();

    return view('employees.index', compact('employees', 'search', 'statusPegawai', 'statusKepegawaian'));
  }

public function create(): View
  {
    return view('employees.create', [
      'departments' => Department::where('code', '!=', 'HUM')->orderBy('name')->get(),
      'positions' => Position::orderBy('name')->get(),
    ]);
  }

public function store(StoreEmployeeRequest $request): RedirectResponse
  {
$data = $request->validated();

    // Jika status pegawai Outsourcing, unit kerja diisi '-'
    if (($data['status_pegawai'] ?? null) === 'Outsourcing') {
      $data['unit_kerja'] = '-';
    }

    // Tentukan department_id berdasarkan unit_kerja (kode)
    $data['department_id'] = Department::where('code', $data['unit_kerja'] ?? '')->value('id');

    Employee::create($data);

    return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
  }

public function edit(Employee $employee): View
  {
    return view('employees.edit', [
      'employee' => $employee,
      'departments' => Department::where('code', '!=', 'HUM')->orderBy('name')->get(),
      'positions' => Position::orderBy('name')->get(),
    ]);
  }

public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
  {
$data = $request->validated();

    // Jika status pegawai Outsourcing, unit kerja diisi '-'
    if (($data['status_pegawai'] ?? null) === 'Outsourcing') {
      $data['unit_kerja'] = '-';
    }

    // Tentukan department_id berdasarkan unit_kerja (kode)
    $data['department_id'] = Department::where('code', $data['unit_kerja'] ?? '')->value('id');

    $employee->update($data);

    return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
  }

  public function destroy(Employee $employee): RedirectResponse
  {
    $employee->delete();

    return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
  }
}
