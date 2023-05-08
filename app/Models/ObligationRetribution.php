<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObligationRetribution extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function contract() {
        return $this->hasMany(Contract::class, 'wajib_retribusi_id', 'id');
    }
}
