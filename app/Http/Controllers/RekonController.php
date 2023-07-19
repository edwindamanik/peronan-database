<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MandatoryRetribution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RekonController extends Controller
{
    public function rekon()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $jenis = DB::table('mandatory_retributions')
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
        $data = DB::table('mandatory_retributions')
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
            ->select(DB::raw('SUM(mandatory_retributions.total_retribusi) AS total_retribusii'))
            ->get();

        $you = DB::table('deposits')
            ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('users', 'deposits.users_id', '=', 'users.id')
            ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
            ->join('users AS officer_users', 'market_officers.users_id', '=', 'officer_users.id')
            ->where('deposits.status', 'disetujui')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->select('deposits.*', 'markets.nama_pasar', 'users.nama', 'officer_users.nama AS officer_name')
            ->select(DB::raw('SUM(deposits.jumlah_setoran) AS setoran'))
            ->get();




        return view('bendahara.rekonsiliasi', compact('data','you','jenis'));
    }

    public function rekondetail()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('mandatory_retributions')
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
            ->select(DB::raw('SUM(mandatory_retributions.total_retribusi) AS total_retribusii'))
            ->get();

        $you = DB::table('deposits')
            ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('users', 'deposits.users_id', '=', 'users.id')
            ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
            ->join('users AS officer_users', 'market_officers.users_id', '=', 'officer_users.id')
            ->where('deposits.status', 'disetujui')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->select('deposits.*', 'markets.nama_pasar', 'users.nama', 'officer_users.nama AS officer_name')
            ->select(DB::raw('SUM(deposits.jumlah_setoran) AS setoran'))
            ->get();




        return view('bendahara.rekonsiliasidetail', compact('data','you'));
    }

}