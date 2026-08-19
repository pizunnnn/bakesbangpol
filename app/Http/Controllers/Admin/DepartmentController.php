<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::withCount('employees')->orderBy('name')->get();
        return view('departments.index', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:departments,code'],
            'description' => ['nullable', 'string'],
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:departments,code,' . $department->id],
            'description' => ['nullable', 'string'],
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->employees()->count() > 0) {
            return redirect()->route('departments.index')->with('error', 'Unit kerja tidak dapat dihapus karena masih digunakan oleh pegawai.');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Unit kerja berhasil dihapus.');
    }
}
