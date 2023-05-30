<?php

namespace App\Exports;

use App\Models\Deposit;
use Maatwebsite\Excel\Concerns\FromCollection;

class laporansetor implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Deposit::all();
    }
}
