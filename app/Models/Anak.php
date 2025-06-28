<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anak extends Model
{
    protected $guarded = [];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function ayah(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'ayah_id');
    }

    public function ibu(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'ibu_id');
    }
}
