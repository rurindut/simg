<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    protected $guarded = [];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
