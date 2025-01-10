<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
  use HasFactory;

  // Kolom yang dapat diisi secara massal
  protected $fillable = [
    'rt_id',
    'jumlah_laki',
    'jumlah_perempuan',
    'jumlah_kk',
    'tanggal',
    'status',
    'created_by',
  ];

  // Relasi ke tabel RT
  public function rt()
  {
    return $this->belongsTo(Rt::class);
  }

  public function getTotalWarga()
  {
    return $this->jumlah_laki + $this->jumlah_perempuan;
  }

  public function createdBy()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
