<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PppkReview;
use App\Models\ReviewDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PppkReviewController extends Controller
{
  /**
   * Halaman utama Laporan Kinerja PPPK.
   * Menampilkan daftar periode, form data pegawai, form tambah kegiatan,
   * dan daftar kegiatan untuk periode yang dipilih.
   */
  public function index(Request $request): View
  {
    $periods = PppkReview::query()
      ->orderByDesc('periode_tahun')
      ->orderByDesc('periode_bulan')
      ->get();

    // Periode terpilih: dari query param, atau periode terbaru.
    $selectedId = $request->query('periode');
    $selected = $periods->firstWhere('id', (int) $selectedId) ?? $periods->first();

    return view('reviews.index', [
      'periods' => $periods,
      'selected' => $selected,
    ]);
  }

  /**
   * Simpan / buat data Laporan Kinerja PPPK untuk periode baru.
   */
  public function storePeriod(Request $request): RedirectResponse
  {
    $data = $request->validate([
      'nama' => ['required', 'string', 'max:255'],
      'nipkkk' => ['required', 'string', 'max:255'],
      'jabatan' => ['required', 'string', 'max:255'],
      'periode_bulan' => ['required', 'string', 'max:20'],
      'periode_tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
      'pptk_nama' => ['required', 'string', 'max:255'],
      'pptk_nip' => ['required', 'string', 'max:255'],
    ]);

    $data['status'] = 'draft';
    $data['evaluation_period'] = $data['periode_bulan'] . ' ' . $data['periode_tahun'];
    $data['year'] = $data['periode_tahun'];

    $review = PppkReview::create($data);

    return redirect()
      ->route('reviews.index', ['periode' => $review->id])
      ->with('success', 'Data Laporan Kinerja PPPK periode ' . $data['evaluation_period'] . ' berhasil disimpan.');
  }

  /**
   * Simpan kegiatan harian ke periode yang dipilih.
   */
  public function storeKegiatan(Request $request): RedirectResponse
  {
    $data = $request->validate([
      'pppk_review_id' => ['required', 'integer', 'exists:pppk_reviews,id'],
      'kegiatan_date' => ['required', 'date'],
      'waktu_mulai' => ['required', 'string', 'max:20'],
      'waktu_selesai' => ['required', 'string', 'max:20'],
      'uraian' => ['required', 'string'],
    ]);

    ReviewDetail::create([
      'pppk_review_id' => $data['pppk_review_id'],
      'kegiatan_date' => $data['kegiatan_date'],
      'kegiatan_time' => $data['waktu_mulai'] . ' - ' . $data['waktu_selesai'],
      'uraian' => $data['uraian'],
    ]);

    return redirect()
      ->route('reviews.index', ['periode' => $data['pppk_review_id']])
      ->with('success', 'Kegiatan kinerja harian berhasil ditambahkan!');
  }

  /**
   * Preview cetak / PDF untuk periode terpilih.
   */
  public function print(Request $request): View
  {
    $review = PppkReview::with('details')
      ->where('id', (int) $request->query('periode'))
      ->firstOrFail();

    // Konversi logo ke base64 untuk embedded image di PDF
    $logoPath = public_path('images/logo-bakesbangpol.png');
    $logoBase64 = '';
    if (file_exists($logoPath)) {
      $logoBase64 = base64_encode(file_get_contents($logoPath));
    }

    return view('reviews.print', [
      'review' => $review,
      'logoBase64' => $logoBase64,
    ]);
  }

  public function destroy(PppkReview $review): RedirectResponse
  {
    // Hapus seluruh kegiatan (detail) terkait periode ini terlebih dahulu.
    $review->details()->delete();

    $review->delete();

    return redirect()->route('reviews.index')->with('success', 'Periode laporan berhasil dihapus.');
  }
}
