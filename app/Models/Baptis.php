<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Baptis extends Model
{
    protected $guarded = [];
    
    public function anggota()
    {
        return $this->belongsTo(\App\Models\Anggota::class);
    }
}
