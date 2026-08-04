<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'user_id' => ['nullable', 'integer', 'exists:users,id'],
      'department_id' => ['nullable', 'integer', 'exists:departments,id'],
      'position_id' => ['nullable', 'integer', 'exists:positions,id'],
'employee_number' => ['required_if:status_pegawai,Pegawai Tetap,P3K Paruh Waktu', 'nullable', 'string', 'max:255', 'unique:employees,employee_number'],
      'full_name' => ['required', 'string', 'max:255'],
      'gender' => ['nullable', 'in:male,female'],
      'birth_place' => ['nullable', 'string', 'max:255'],
      'birth_date' => ['nullable', 'date'],
      'phone' => ['nullable', 'string', 'max:50'],
      'email' => ['nullable', 'email', 'max:255', 'unique:employees,email'],
      'address' => ['nullable', 'string'],
      'join_date' => ['nullable', 'date'],
'employment_status' => ['required', 'string', 'max:255'],
      'status_pegawai' => ['nullable', 'string', 'max:255'],
      'unit_kerja' => ['nullable', 'string', 'max:255'],
      'photo' => ['nullable', 'string', 'max:255'],
    ];
  }
}
