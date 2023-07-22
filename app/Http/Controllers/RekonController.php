<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MandatoryRetribution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class RekonController extends Controller
{
    public function rekon()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
        $currentMonth = Carbon::now()->month;

        $realCash = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->where('users.kabupaten_id', $kabupatenId)
                ->where('mandatory_retributions.status_pembayaran', 'sudah_dibayar')
                ->where('mandatory_retributions.metode_pembayaran', 'cash')
                ->whereRaw('MONTH(mandatory_retributions.tanggal_pembayaran) = ?', [$currentMonth])
                ->selectRaw('SUM(mandatory_retributions.biaya_retribusi) as total_biaya')
                ->first();

        $harianTotal = DB::table('daily_retributions')
                    ->join('markets', 'markets.id', '=', 'daily_retributions.pasar_id')
                    ->join('market_groups', 'market_groups.id', '=', 'markets.kelompok_pasar_id')
                    ->where('market_groups.kabupaten_id', $kabupatenId)
                    ->where('daily_retributions.status', 'sudah_bayar')
                    ->whereRaw('MONTH(daily_retributions.created_at) = ?', [$currentMonth])
                    ->selectRaw('SUM(daily_retributions.biaya_retribusi) as total_harian')
                    ->first();
        
        $totalCash = $realCash->total_biaya + $harianTotal->total_harian;

        $depositCash = DB::table('deposits')
                    ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
                    ->join('market_groups', 'market_groups.id', '=', 'markets.kelompok_pasar_id')
                    ->where('market_groups.kabupaten_id', $kabupatenId)
                    ->where('deposits.status', 'disetujui')
                    ->where('deposits.wajib_retribusi_id', null)
                    ->selectRaw('SUM(deposits.jumlah_setoran) as total_setoran')
                    ->first();

        // dd($depositCash);

        $realVa = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->where('users.kabupaten_id', $kabupatenId)
                ->where('mandatory_retributions.status_pembayaran', 'sudah_dibayar')
                ->where('mandatory_retributions.metode_pembayaran', 'virtual_account')
                ->whereRaw('MONTH(mandatory_retributions.tanggal_pembayaran) = ?', [$currentMonth])
                ->selectRaw('SUM(mandatory_retributions.biaya_retribusi) as total_biaya')
                ->get();

        $realQris = DB::table('mandatory_retributions')
                ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
                ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
                ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
                ->where('users.kabupaten_id', $kabupatenId)
                ->where('mandatory_retributions.status_pembayaran', 'sudah_dibayar')
                ->where('mandatory_retributions.metode_pembayaran', 'qris')
                ->whereRaw('MONTH(mandatory_retributions.tanggal_pembayaran) = ?', [$currentMonth])
                ->selectRaw('SUM(mandatory_retributions.biaya_retribusi) as total_biaya')
                ->get();

        $jenis = DB::table('mandatory_retributions')
            ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
            ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
            ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
            ->join('units', 'units.id', '=', 'contracts.unit_id')
            ->join('markets', 'markets.id', '=', 'units.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->where('mandatory_retributions.status_pembayaran', '=', 'sudah_dibayar')
            ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
            ->get();

        $manda = DB::table('mandatory_retributions')
            ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
            ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
            ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
            ->join('units', 'units.id', '=', 'contracts.unit_id')
            ->join('markets', 'markets.id', '=', 'units.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->where('mandatory_retributions.status_pembayaran', '=', 'sudah_dibayar')
            ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
            ->get();

        $depo = DB::table('deposits')
            ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('users', 'deposits.users_id', '=', 'users.id')
            ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
            ->join('users AS officer_users', 'market_officers.users_id', '=', 'officer_users.id')
            ->where('deposits.status', 'disetujui')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->where(DB::raw('MONTH(deposits.tanggal_penyetoran)'), '=', $currentMonth)
            ->select('deposits.*', 'markets.nama_pasar', 'users.nama', 'officer_users.nama AS officer_name')
            ->get();

        return view('bendahara.rekonsiliasi', compact('totalCash', 'depositCash'));
    }

    public function rekondetail()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
        
        $currentMonth = Carbon::now()->format('m');

        $pasar = DB::table('mandatory_retributions')
        ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
        ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
        ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
        ->join('units', 'units.id', '=', 'contracts.unit_id')
        ->join('markets', 'markets.id', '=', 'units.pasar_id')
        ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
        ->where('market_groups.kabupaten_id', $kabupatenId)
        ->where('mandatory_retributions.status_pembayaran', '=', 'sudah_dibayar')
        ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
        ->get();

        $manda = DB::table('mandatory_retributions')
            ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
            ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
            ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
            ->join('units', 'units.id', '=', 'contracts.unit_id')
            ->join('markets', 'markets.id', '=', 'units.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->where('mandatory_retributions.status_pembayaran', '=', 'sudah_dibayar')
            ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
            ->get();

        $depo = DB::table('deposits')
            ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('users', 'deposits.users_id', '=', 'users.id')
            ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
            ->join('users AS officer_users', 'market_officers.users_id', '=', 'officer_users.id')
            ->where('deposits.status', 'disetujui')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->select('deposits.*', 'markets.nama_pasar', 'users.nama', 'officer_users.nama AS officer_name')
            ->where(DB::raw('MONTH(deposits.tanggal_penyetoran)'), '=', $currentMonth)
            ->get();


        return view('bendahara.rekonsiliasidetail', compact('manda','depo','pasar'));
    }

    public function rekonpetugas()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
        
        $currentMonth = Carbon::now()->format('m');

        $petugas = DB::table('mandatory_retributions')
        ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
        ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
        ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
        ->join('units', 'units.id', '=', 'contracts.unit_id')
        ->join('markets', 'markets.id', '=', 'units.pasar_id')
        ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
        ->where('market_groups.kabupaten_id', $kabupatenId)
        ->where('mandatory_retributions.status_pembayaran', '=', 'sudah_dibayar')
        ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
        ->get();

        $manda = DB::table('mandatory_retributions')
            ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
            ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
            ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
            ->join('units', 'units.id', '=', 'contracts.unit_id')
            ->join('markets', 'markets.id', '=', 'units.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->where('mandatory_retributions.status_pembayaran', '=', 'sudah_dibayar')
            ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
            ->get();

        $depo = DB::table('deposits')
            ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('users', 'deposits.users_id', '=', 'users.id')
            ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
            ->join('users AS officer_users', 'market_officers.users_id', '=', 'officer_users.id')
            ->where('deposits.status', 'disetujui')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->select('deposits.*', 'markets.nama_pasar', 'users.nama', 'officer_users.nama AS officer_name')
            ->where(DB::raw('MONTH(deposits.tanggal_penyetoran)'), '=', $currentMonth)
            ->get();


        return view('bendahara.rekonsiliasipetugas', compact('manda','depo','petugas'));
    }

    public function rekonwajibretri()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
        
        $currentMonth = Carbon::now()->format('m');

        $wajibr = DB::table('mandatory_retributions')
        ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
        ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
        ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
        ->join('units', 'units.id', '=', 'contracts.unit_id')
        ->join('markets', 'markets.id', '=', 'units.pasar_id')
        ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
        ->where('market_groups.kabupaten_id', $kabupatenId)
        ->where('mandatory_retributions.status_pembayaran', '=', 'sudah_dibayar')
        ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
        ->get();

        $manda = DB::table('mandatory_retributions')
            ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
            ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
            ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
            ->join('units', 'units.id', '=', 'contracts.unit_id')
            ->join('markets', 'markets.id', '=', 'units.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->where('mandatory_retributions.status_pembayaran', '=', 'sudah_dibayar')
            ->where('mandatory_retributions.metode_pembayaran', '=', 'CASH')
            ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
            ->get();

        $depo = DB::table('deposits')
            ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('users', 'deposits.users_id', '=', 'users.id')
            ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
            ->join('users AS officer_users', 'market_officers.users_id', '=', 'officer_users.id')
            ->where('deposits.status', 'disetujui')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->where(DB::raw('MONTH(deposits.tanggal_penyetoran)'), '=', $currentMonth)
            ->select('deposits.*', 'markets.nama_pasar', 'users.nama', 'officer_users.nama AS officer_name')
            ->get();


        return view('bendahara.rekonsiliasiwajibretri', compact('manda','depo','wajibr'));
    }

}