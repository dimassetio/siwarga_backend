<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rt extends Model
{
  use HasFactory;

  protected $fillable = ['rw_id', 'number'];

  public function rw()
  {
    return $this->belongsTo(Rw::class);
  }

  public function warga()
  {
    return $this->hasOne(Warga::class);
  }

  public function approvedWarga()
  {
    return $this->hasOne(Warga::class)->where('status', 'Disetujui');
  }

  public function users()
  {
    return $this->belongsToMany(User::class, 'user_rt');
  }
}
