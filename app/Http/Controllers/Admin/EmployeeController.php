<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
  public function index(): View
  {
    // Auto-check pension status for all employees
    Employee::whereNotNull('birth_date')->get()->each(function ($emp) {
      $emp->checkAndUpdateStatusPensiun();
    });

    $search = request('search');
    $statusPegawai = request('status_pegawai');
    $statusKepegawaian = request('status_kepegawaian');
    $departmentId = request('department_id');
    $positionId = request('position_id');

    $employees = Employee::with(['department', 'position', 'user', 'salaryHistories', 'rankHistories'])
      ->when($search, function ($query) use ($search) {
        $query->where(function ($q) use ($search) {
          $q->where('full_name', 'like', "%{$search}%")
            ->orWhere('employee_number', 'like', "%{$search}%")
            ->orWhere('unit_kerja', 'like', "%{$search}%")
            ->orWhere('status_pegawai', 'like', "%{$search}%")
            ->orWhere('pangkat_golongan', 'like', "%{$search}%")
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
      ->when($departmentId, function ($query) use ($departmentId) {
        $query->where('department_id', $departmentId);
      })
      ->when($positionId, function ($query) use ($positionId) {
        $query->where('position_id', $positionId);
      })
      ->latest()
      ->paginate(15)
      ->withQueryString();

    $departments = Department::orderBy('name')->get();
    $positions = Position::orderBy('name')->get();

    return view('employees.index', compact(
      'employees',
      'search',
      'statusPegawai',
      'statusKepegawaian',
      'departmentId',
      'positionId',
      'departments',
      'positions'
    ));
  }

  public function show(Employee $employee): View
  {
    // Auto-check pension status for this employee
    $employee->checkAndUpdateStatusPensiun();

    $employee->load([
      'department',
      'position',
      'trainings',
      'salaryHistories',
      'rankHistories',
      'positionHistories',
      'retirements',
    ]);

    $departments = Department::orderBy('name')->get();

    return view('employees.show', compact('employee', 'departments'));
  }

  public function create(): View
  {
    return view('employees.create', [
      'departments' => Department::orderBy('name')->get(),
      'positions' => Position::orderBy('name')->get(),
    ]);
  }

  public function store(StoreEmployeeRequest $request): RedirectResponse
  {
    $data = $request->validated();

    if (!empty($data['department_id'])) {
      $dept = Department::find($data['department_id']);
      if ($dept) {
        $data['unit_kerja'] = $dept->name;
      }
    } elseif (!empty($data['unit_kerja'])) {
      $dept = Department::where('code', $data['unit_kerja'])->orWhere('name', $data['unit_kerja'])->first();
      if ($dept) {
        $data['department_id'] = $dept->id;
      }
    }

    $employee = Employee::create($data);
    $employee->checkAndUpdateStatusPensiun();

    return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil ditambahkan.');
  }

  public function edit(Employee $employee): View
  {
    return view('employees.edit', [
      'employee' => $employee,
      'departments' => Department::orderBy('name')->get(),
      'positions' => Position::orderBy('name')->get(),
    ]);
  }

  public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
  {
    $data = $request->validated();

    if (!empty($data['department_id'])) {
      $dept = Department::find($data['department_id']);
      if ($dept) {
        $data['unit_kerja'] = $dept->name;
      }
    } elseif (!empty($data['unit_kerja'])) {
      $dept = Department::where('code', $data['unit_kerja'])->orWhere('name', $data['unit_kerja'])->first();
      if ($dept) {
        $data['department_id'] = $dept->id;
      }
    }

    $employee->update($data);
    $employee->checkAndUpdateStatusPensiun();

    return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil diperbarui.');
  }

  public function destroy(Employee $employee): RedirectResponse
  {
    $employee->delete();

    return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil dihapus.');
  }

  /**
   * Cetak PDF Data Pegawai
   */
  public function exportPdf(Request $request): Response
  {
    $statusPegawai = $request->query('status_pegawai');
    $departmentId = $request->query('department_id');
    $positionId = $request->query('position_id');

    $query = Employee::with(['department', 'position'])
      ->when($statusPegawai, function ($q) use ($statusPegawai) {
        $q->where('status_pegawai', $statusPegawai);
      })
      ->when($departmentId, function ($q) use ($departmentId) {
        $q->where('department_id', $departmentId);
      })
      ->when($positionId, function ($q) use ($positionId) {
        $q->where('position_id', $positionId);
      })
      ->orderBy('full_name', 'asc');

    $employees = $query->get();

    $filterUnit = $departmentId ? Department::find($departmentId)?->name : null;
    $filterJabatan = $positionId ? Position::find($positionId)?->name : null;

    $pdf = Pdf::loadView('employees.pdf', compact('employees', 'statusPegawai', 'filterUnit', 'filterJabatan'))
      ->setPaper('a4', 'landscape');

    $filename = 'Data_Pegawai_' . ($statusPegawai ? str_replace(' ', '_', $statusPegawai) : 'Semua') . '_' . date('Ymd_His') . '.pdf';

    return $pdf->stream($filename);
  }
}
