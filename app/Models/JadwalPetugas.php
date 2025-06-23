<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPetugas extends Model
{
    protected $guarded = [];

    public function jadwalIbadah()
    {
        return $this->belongsTo(JadwalIbadah::class, 'jadwal_ibadah_id');
    }

    public function pelayanan()
    {
        return $this->belongsTo(DaftarPelayanan::class, 'daftar_pelayanan_id');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }
}
