<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regency extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function jenisUnit()
    {
        return $this->hasMany(UnitType::class);
    }

    public function pasar() 
    {
        return $this->hasMany(Market::class);
    }
}
