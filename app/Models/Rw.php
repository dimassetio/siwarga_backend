<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rw extends Model
{
  use HasFactory;

  protected $fillable = ['number'];

  public function rts()
  {
      return $this->hasMany(Rt::class);
  }

  public function users()
    {
        return $this->belongsToMany(User::class, 'user_rw');
    }
}