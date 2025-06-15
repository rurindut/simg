<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atestasi extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
