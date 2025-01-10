<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Rw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RwController extends BaseController
{
  public function index(Request $request)
  {
    $rws = Rw::all();

    return $this->sendResponse($rws, 'Berhasil memuat data rw.');
  }

  public function store(Request $request)
  {
    $input = $request->all();

    $validator = Validator::make($input, [
      'number' => 'required',
    ]);

    if ($validator->fails()) {
      return $this->sendError('Validation Error.', $validator->errors());
    }

    $rw = Rw::create($input);

    return $this->sendResponse($rw, 'Rw berhasil dibuat.');
  }

  public function show($id)
  {
    $rw = Rw::findOrFail($id);

    return $this->sendResponse($rw, 'Rw berhasil dimuat.');
  }

  public function update(Request $request, $id)
  {
    $input = $request->all();

    $validator = Validator::make($input, [
      'number' => 'required',
    ]);

    if ($validator->fails()) {
      return $this->sendError('Validation Error.', $validator->errors());
    }

    $rw = Rw::findOrFail($id);
    if ($rw) {
      $rw->number = $input['number'];
      $rw->save();
      return $this->sendResponse($rw, 'Rw berhasil diperbarui.');
    }

    return $this->sendError('Data rw tidak ditemukan');
  }

  public function destroy(Rw $rw)
  {
    $rw->delete();

    return $this->sendResponse([], 'Rw deleted successfully.');
  }
}
