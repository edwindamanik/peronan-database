<?php

namespace App\Exports;

use DB;
use App\Models\MandatoryRetribution;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class nonharian implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    private $startDate;
    private $endDate;
    private $pasarId;

    public function __construct($startDate, $endDate, $pasarId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->pasarId = $pasarId;
    }

    public function query()
    {

    
        return DB::table('mandatory_retributions')
        ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
        ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
        ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
        ->join('units', 'units.id', '=', 'contracts.unit_id')
        ->join('markets', 'markets.id', '=', 'units.pasar_id')
        ->where('markets.id', $this->pasarId)
        ->whereBetween('mandatory_retributions.tanggal_pembayaran', [$this->startDate, $this->endDate])
        ->where('mandatory_retributions.status_pembayaran','=', 'belum_dibayar')
        ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
        ->orderBy('mandatory_retributions.tanggal_pembayaran');;
    
    }
    


    public function map($row): array
    {
        return [
            $row->no_tagihan,
            $row->nama,
            $row->no_unit,
            $row->total_retribusi,
            $row->jatuh_tempo,
        ];
    }

    public function headings(): array
    {
        return [
            'No Tagihan',
            'Wajib Retribusi',
            'Unit',
            'Total Retribusi',
            'Jatuh Tempo',
        ];
    }
}