<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'asset_category_id' => ['nullable', 'integer', 'exists:asset_categories,id'],
      'asset_code' => ['nullable', 'string', 'max:255', 'unique:assets,asset_code'],
      'category' => ['nullable', 'string', 'max:255'],
      'brand' => ['nullable', 'string', 'max:255'],
      'model' => ['nullable', 'string', 'max:255'],
      'serial_number' => ['nullable', 'string', 'max:255', 'unique:assets,serial_number'],
      'purchase_date' => ['nullable', 'date'],
      'purchase_price' => ['nullable', 'numeric', 'min:0'],
'condition' => ['nullable', 'string', 'max:255'],
      'status' => ['nullable', 'string', 'max:255'],
      'current_employee_id' => ['nullable', 'integer', 'exists:employees,id'],
      'location' => ['nullable', 'string', 'max:255'],
      'photo' => ['nullable', 'string', 'max:255'],
      // BMD fields
      'kode_barang' => ['required', 'string', 'max:255'],
      'no_register' => ['nullable', 'string', 'max:255'],
      'nama_barang' => ['required', 'string', 'max:255'],
      'merk_tipe' => ['nullable', 'string', 'max:255'],
      'spesifikasi' => ['nullable', 'string'],
      'cara_perolehan' => ['nullable', 'string', 'max:255'],
      'tahun_perolehan' => ['nullable', 'integer', 'min:1900', 'max:2100'],
      'nilai_perolehan' => ['nullable', 'numeric', 'min:0'],
      'keadaan' => ['nullable', 'string', 'max:255'],
'umur_ekonomis' => ['nullable', 'integer', 'min:0'],
      'jumlah_unit' => ['nullable', 'integer', 'min:1'],
    ];
  }
}
