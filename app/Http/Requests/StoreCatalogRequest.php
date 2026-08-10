<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCatalogRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'kode_barang' => ['required', 'string', 'max:255', 'unique:asset_catalog,kode_barang'],
      'nama_barang' => ['required', 'string', 'max:255'],
    ];
  }
}
