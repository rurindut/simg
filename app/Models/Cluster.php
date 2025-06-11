<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $guarded = [];
    
    public function regions()
    {
        return $this->belongsTo(Region::class);
    }
}
