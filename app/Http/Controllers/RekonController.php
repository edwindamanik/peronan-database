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

        $data = DB::table('mandatory_retributions')
            ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
            ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
            ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
            ->join('units', 'units.id', '=', 'contracts.unit_id')
            ->join('markets', 'markets.id', '=', 'units.pasar_id')
            ->where('mandatory_retributions.status_pembayaran', '=', 'belum_dibayar')
            ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
            ->select('SUM(mandatory_retributions.total_retribusi) AS total_retribusi')
            ->first();



        dd($data);

        return view('bendahara.rekonsiliasi', compact('data'));
    }

}