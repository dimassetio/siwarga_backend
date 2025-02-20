<?php

namespace App\Http\Controllers\Api;

use App\Models\Rt;
use App\Models\Warga;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WargaController extends BaseController
{
  public function index(Request $request)
  {
    $query = Warga::query();

    // Filter by 'rt' if provided
    if ($request->filled('rt')) {
      $rtId = $request->input('rt');
      $query->where('rt_id', $rtId);
    }
    // Filter by 'rw' if provided
    else if ($request->filled('rw')) {
      $rwId = $request->input('rw');
      $query->whereHas('rt', function ($subQuery) use ($rwId) {
        $subQuery->where('rw_id', $rwId);
      });
    }

    // Include relationships and apply ordering
    $wargas = $query->with('rt.rw')
      ->orderBy('status')
      ->orderBy('rt_id')
      ->get()
      ->map(function ($warga) {
        // Add the accessor attribute to the model
        $warga->totalWarga = $warga->getTotalWarga();
        $warga->rt_number = $warga->rt->number;
        $warga->rw_id = $warga->rt->rw->id;
        $warga->rw_number = $warga->rt->rw->number;
        return $warga;
      });

    // Calculate the summary
    $total_laki = $wargas->sum('jumlah_laki');
    $total_perempuan = $wargas->sum('jumlah_perempuan');
    $summary = [
      'total_laki' => $total_laki,
      'total_perempuan' => $total_perempuan,
      'total_warga' => $total_laki + $total_perempuan,
      'total_kk' => $wargas->sum('jumlah_kk'),
    ];

    return $this->sendResponse([
      'wargas' => $wargas,
      'summary' => $summary,
    ], 'Berhasil memuat data warga');
  }

  public function store(Request $request)
  {
    //
  }

  public function save(Request $request): JsonResponse
  {
    try {
      // Validate the incoming request
      $validatedData = $request->validate([
        'rt_id' => 'required|exists:rts,id',
        'jumlah_laki' => 'required|integer|min:0',
        'jumlah_perempuan' => 'required|integer|min:0',
        'jumlah_kk' => 'required|integer|min:0',
      ]);

      // Check if a Warga record already exists for the given rt_id
      $existingWarga = Warga::where('rt_id', $validatedData['rt_id'])->first();

      $validatedData['status'] = "Diajukan";
      $validatedData['created_by'] = $request->user()->id;

      if ($existingWarga) {
        // Update the existing record
        $existingWarga->update($validatedData);
        return $this->sendResponse($existingWarga, 'Data warga berhasil diupdate.');
      }


      // Create a new Warga record
      $warga = Warga::create($validatedData);

      return $this->sendResponse($warga, 'Data warga berhasil disimpan.');
    } catch (\Throwable $th) {
      return $this->sendError($th, 'Gagal menyimpan data warga.');
    }
  }

  public function changeStatus(Request $request, $id)
  {
    try {
      // Validate the incoming request
      $validatedData = $request->validate([
        'status' => 'required|string|in:Diajukan,Ditolak,Disetujui',
      ]);

      // Check if a Warga record already exists for the given rt_id
      $existingWarga = Warga::findOrFail($id);

      if ($existingWarga) {
        // Update the existing record
        $existingWarga->update($validatedData);
        return $this->sendResponse($existingWarga, 'Status data warga berhasil diupdate.');
      }
      return $this->sendError([], 'Data warga tidak ditemukan.');
    } catch (\Throwable $th) {
      return $this->sendError($th, 'Gagal menyimpan data warga.');
    }
  }

  public function show($id)
  {
    try {
      $warga = Warga::findOrFail($id);
      return $this->sendResponse($warga, "Berhasil memuat data warga");
    } catch (\Throwable $th) {
      return $this->sendError($th, "Gagal memuat data warga");
    }
  }

  public function update(Request $request, Warga $warga)
  {
    //
  }

  public function destroy($id)
  {
    try {
      $warga = Warga::findOrFail($id);
      $warga->delete();
      return $this->sendResponse([], "Berhasil memuat data warga");
    } catch (\Throwable $th) {
      return $this->sendError($th, "Gagal memuat data warga");
    }
  }

  public function summary() {}


  public function downloadPdf()
  {
    $data = Rt::with(['rw', 'approvedWarga.createdBy'])->get();

    $wargas = $data->pluck('approvedWarga')->flatten();
    $total_laki = $wargas->sum('jumlah_laki');
    $total_perempuan = $wargas->sum('jumlah_perempuan');
    $summary = [
      'total_laki' => $total_laki,
      'total_perempuan' => $total_perempuan,
      'total_warga' => $total_laki + $total_perempuan,
      'total_kk' => $wargas->sum('jumlah_kk'),
    ];
    // Load the view and pass data, setting the paper size and orientation
    $pdf = Pdf::loadView('laporan.pdf', compact('data', 'summary'))->setPaper('a4', 'landscape');

    // Return the generated PDF as a response
    return $pdf->download('laporan_penduduk.pdf');
  }
}
