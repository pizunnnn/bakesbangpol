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
    $employees = Employee::with(['department', 'position', 'user'])->latest()->paginate(10);

    return view('employees.index', compact('employees'));
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
