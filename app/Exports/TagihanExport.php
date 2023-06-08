<?php

namespace App\Exports;

use App\Models\DailyRetribution;
use DB;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;


class TagihanExport implements FromQuery, WithMapping, WithHeadings
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

    
        return DB::table('daily_retributions')
        ->join('units', 'units.id', '=', 'daily_retributions.unit_id')
        ->join('markets', 'markets.id', '=', 'daily_retributions.pasar_id')
        ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
        ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
        ->join('users', 'users.id', '=', 'market_officers.users_id')
        ->join('users AS officer', 'market_officers.users_id', '=', 'officer.id')
        ->where('markets.id', $this->pasarId)
        ->whereBetween('daily_retributions.tanggal', [$this->startDate, $this->endDate])
        ->select('daily_retributions.*', 'markets.*', 'users.nama', 'officer.nama AS officers', 'units.*')
        ->orderBy('daily_retributions.tanggal');
    
    }
    


    public function map($row): array
    {
        return [
            $row->no_bukti_pembayaran,
            $row->nama_pasar,
            $row->officers,
            $row->no_unit,
            $row->tanggal,
            $row->biaya_retribusi,
        ];
    }

    public function headings(): array
    {
        return [
            'No Bukti Bayar',
            'Nama Pasar',
            'Petugas',
            'Unit',
            'Tanggal',
            'Biaya Retribusi',
        ];
    }
}