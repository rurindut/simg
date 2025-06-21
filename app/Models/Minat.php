<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Minat extends Model
{
    protected $guarded = [];

    public function anggotas()
    {
        return $this->belongsToMany(Anggota::class);
    }
}
