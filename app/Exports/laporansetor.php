<?php

namespace App\Exports;

use App\Models\Deposit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class laporansetor implements FromQuery, WithMapping, WithHeadings
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
        return Deposit::query()
            ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
            ->join('users', 'users.id', '=', 'deposits.users_id')
            ->where('deposits.status', 'disetujui')
            ->where('markets.id', $this->pasarId)
            ->whereBetween('deposits.tanggal_disetor', [$this->startDate, $this->endDate])
            ->select('markets.nama_pasar', 'users.nama', 'deposits.jumlah_setoran', 'deposits.penyetoran_melalui', 'deposits.tanggal_penyetoran', 'deposits.tanggal_disetor');
    }

    public function map($row): array
    {
        return [
            $row->nama_pasar,
            $row->nama,
            $row->jumlah_setoran,
            $row->penyetoran_melalui,
            $row->tanggal_penyetoran,
            $row->tanggal_disetor,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Pasar',
            'Petugas',
            'Jumlah setoran',
            'Penyetoran Melalui',
            'Tanggal Penyetoran',
            'Tanggal disetor',
        ];
    }
}
