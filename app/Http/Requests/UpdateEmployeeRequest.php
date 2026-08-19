<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $employeeId = $this->route('employee')?->id;

    return [
      'user_id' => ['nullable', 'integer', 'exists:users,id'],
      'department_id' => ['nullable', 'integer', 'exists:departments,id'],
      'position_id' => ['nullable', 'integer', 'exists:positions,id'],
      'pangkat_golongan' => ['nullable', 'string', 'max:255'],
      'employee_number' => ['required_if:status_pegawai,PNS,PPPK,PPPK Paruh Waktu', 'nullable', 'string', 'max:255', 'unique:employees,employee_number,' . $employeeId],
      'full_name' => ['required', 'string', 'max:255'],
      'gender' => ['nullable', 'in:male,female'],
      'birth_place' => ['nullable', 'string', 'max:255'],
      'birth_date' => ['nullable', 'date'],
      'phone' => ['nullable', 'string', 'max:50'],
      'email' => ['nullable', 'email', 'max:255', 'unique:employees,email,' . $employeeId],
      'address' => ['nullable', 'string'],
      'join_date' => ['nullable', 'date'],
      'employment_status' => ['required', 'string', 'max:255'],
      'status_pegawai' => ['required', 'string', 'in:PNS,PPPK,PPPK Paruh Waktu'],
      'unit_kerja' => ['nullable', 'string', 'max:255'],
      'photo' => ['nullable', 'string', 'max:255'],
    ];
  }
}
