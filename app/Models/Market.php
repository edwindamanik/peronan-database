<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Market extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function market_officer() {
        return $this->belongsTo(MarketOfficer::class);
    }

    public function kabupaten() 
    {
        return $this->belongsTo(Regency::class);
    }

    public function unit() 
    {
        return $this->hasMany(Unit::class);
    }

}
