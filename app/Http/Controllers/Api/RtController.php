<?php

namespace App\Http\Controllers\Api;

use App\Models\Rt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RtController extends BaseController
{
  public function index(Request $request)
  {
    $rts = Rt::all();

    return $this->sendResponse($rts, 'Berhasil memuat data rw.');
  }

  public function store(Request $request)
  {
    $input = $request->all();

    $validator = Validator::make($input, [
      'rw_id' => 'required',
      'number' => 'required',
    ]);

    if ($validator->fails()) {
      return $this->sendError('Validation Error.', $validator->errors());
    }

    $rt = Rt::create($input);

    return $this->sendResponse($rt, 'Rt berhasil dibuat.');
  }

  public function show($id)
  {
    $rt = Rt::findOrFail($id);

    return $this->sendResponse($rt, 'Rt berhasil dimuat.');
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

    $rt = Rt::findOrFail($id);
    if ($rt) {
      $rt->number = $input['number'];
      $rt->save();
      return $this->sendResponse($rt, 'Rt berhasil diperbarui.');
    }

    return $this->sendError('Data rt tidak ditemukan');
  }

  public function destroy(Rt $rt)
  {
    $rt->delete();

    return $this->sendResponse([], 'Rt berhasil dihapus.');
  }
}
