<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function kabupaten()
    {
        return $this->belongsTo(Regency::class);
    }

    public function biayaRetribusi() 
    {
        return $this->hasMany(RetributionFee::class);
    }
}
