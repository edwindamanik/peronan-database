<?php

namespace App\Exports;

use App\Models\DailyRetribution;
use Maatwebsite\Excel\Concerns\FromCollection;

class pembatalan implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DailyRetribution::all();
    }
}
