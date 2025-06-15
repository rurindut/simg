<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $guarded = [];

    public function suku() { return $this->belongsTo(Suku::class); }
    public function pendidikan() { return $this->belongsTo(Pendidikan::class); }
    public function hobi() { return $this->belongsTo(Hobi::class); }
    public function minat() { return $this->belongsTo(Minat::class); }
    public function region() { return $this->belongsTo(Region::class); }
    public function cluster() { return $this->belongsTo(Cluster::class); }

    public function baptis()
    {
        return $this->hasMany(\App\Models\Baptis::class);
    }

    public function baptisAnak()
    {
        return $this->hasOne(Baptis::class)->where('jenis', 'anak');
    }

    public function baptisSidi()
    {
        return $this->hasOne(Baptis::class)->where('jenis', 'sidi');
    }

    public function atestasis()
    {
        return $this->hasMany(Atestasi::class, 'anggota_id');
    }

    public function orangtuas()
    {
        return $this->hasMany(OrangTua::class);
    }

    public function ayah()
    {
        return $this->hasOne(OrangTua::class)->where('hubungan', 'ayah');
    }

    public function ibu()
    {
        return $this->hasOne(OrangTua::class)->where('hubungan', 'ibu');
    }

    public function pasangan()
    {
        return $this->hasOne(Pasangan::class);
    }

    public function anaks()
    {
        return $this->hasMany(Anak::class);
    }

    public function pengalamanGerejawis()
    {
        return $this->hasMany(PengalamanGerejawi::class);
    }

    public function aktivitasSosials()
    {
        return $this->hasMany(AktivitasSosial::class);
    }

    public function pekerjaans()
    {
        return $this->hasMany(Pekerjaan::class);
    }

    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }

}
