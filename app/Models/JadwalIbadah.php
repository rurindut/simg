<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalIbadah extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];
    
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function jadwalPetugas()
    {
        return $this->hasMany(JadwalPetugas::class, 'jadwal_ibadah_id');
    }
}
