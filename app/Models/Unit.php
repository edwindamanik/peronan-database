<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function pasar() {
        return $this->belongsTo(Market::class);
    }

    public function jenisUnit()
    {
        return $this->belongsTo(UnitType::class);
    }
}
