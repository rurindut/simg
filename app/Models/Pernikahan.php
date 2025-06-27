<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pernikahan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal_catatan_sipil' => 'date',
        'tanggal_pemberkatan' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function suami()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id_suami');
    }

    public function istri()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id_istri');
    }
}
