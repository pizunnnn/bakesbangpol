<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePppkReviewRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'employee_id' => ['required', 'integer', 'exists:employees,id'],
      'evaluation_period' => ['required', 'string', 'max:255'],
      'year' => ['required', 'integer', 'min:2020'],
      'work_target_achievement' => ['nullable', 'numeric', 'min:0', 'max:100'],
      'work_quality' => ['nullable', 'numeric', 'min:0', 'max:100'],
      'discipline' => ['nullable', 'numeric', 'min:0', 'max:100'],
      'attendance' => ['nullable', 'numeric', 'min:0', 'max:100'],
      'communication' => ['nullable', 'numeric', 'min:0', 'max:100'],
      'teamwork' => ['nullable', 'numeric', 'min:0', 'max:100'],
      'responsibility' => ['nullable', 'numeric', 'min:0', 'max:100'],
      'innovation' => ['nullable', 'numeric', 'min:0', 'max:100'],
      'leadership' => ['nullable', 'numeric', 'min:0', 'max:100'],
      'comments' => ['nullable', 'string'],
      'supporting_documents' => ['nullable', 'array'],
      'final_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
      'status' => ['required', 'in:draft,submitted,reviewed,approved,rejected'],
      'reviewed_by' => ['nullable', 'integer', 'exists:users,id'],
      'reviewed_at' => ['nullable', 'date'],
    ];
  }
}
