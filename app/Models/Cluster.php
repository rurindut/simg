<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $guarded = [];
    
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function organization()
    {
        return $this->hasOneThrough(
            \App\Models\Organization::class,
            \App\Models\Region::class,
            'id',               // Foreign key on Region (region.id)
            'id',               // Foreign key on Organization (organization.id)
            'region_id',        // Local key on Cluster (region_id)
            'organization_id'   // Local key on Region (organization_id)
        );
    }
}
