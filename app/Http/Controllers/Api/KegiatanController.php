<?php

namespace App\Http\Controllers\Api;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends BaseController
{
  public function index()
  {
    $kegiatan = Kegiatan::all();
    return $this->sendResponse($kegiatan, 'Berhasil memuat kegiatan');
  }

  // Store a newly created resource in storage
  public function store(Request $request)
  {
    $request->validate([
      'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
      'caption' => 'required|string',
    ]);

    // Handle image upload
    $imagePath = $request->file('foto')->store('kegiatan_images', 'public');

    // Create new Kegiatan
    $kegiatan = new Kegiatan();
    $kegiatan->foto = $imagePath;
    $kegiatan->caption = $request->caption;
    $kegiatan->save();

    return $this->sendResponse($kegiatan, 'Berhasil menyimpan kegiatan');
  }

  // Display the specified resource
  public function show(Kegiatan $kegiatan)
  {
    return $this->sendResponse($kegiatan, 'Berhasil memuat kegiatan');
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      'caption' => 'nullable|string',
    ]);

    $kegiatan = Kegiatan::findOrFail($id);

    // Update caption if provided
    if ($request->has('caption')) {
      $kegiatan->caption = $request->caption;
    }

    // Handle image update
    if ($request->hasFile('foto')) {
      // Delete the old image
      if ($kegiatan->foto && Storage::disk('public')->exists($kegiatan->foto)) {
        Storage::disk('public')->delete($kegiatan->foto);
      }

      // Store the new image
      $imagePath = $request->file('foto')->store('kegiatan_images', 'public');
      $kegiatan->foto = $imagePath;
    }

    $kegiatan->save();

    return $this->sendResponse($kegiatan, 'Berhasil mengupdate kegiatan');
  }


  // Remove the specified resource from storage
  public function destroy(Kegiatan $kegiatan)
  {
    // Delete the image file
    if ($kegiatan->foto && Storage::disk('public')->exists($kegiatan->foto)) {
      Storage::disk('public')->delete($kegiatan->foto);
    }

    $kegiatan->delete();
    return $this->sendResponse($kegiatan, 'Berhasil menghapus kegiatan');
  }
}
