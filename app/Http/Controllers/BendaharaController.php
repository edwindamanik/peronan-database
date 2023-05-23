<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BendaharaController extends Controller
{
    public function confirmDeposit() {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('deposits')
                ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
                ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
                ->join('users', 'deposits.users_id', '=', 'users.id')
                ->where('market_groups.kabupaten_id', $kabupatenId)
                ->select('deposits.*', 'markets.nama_pasar', 'users.nama')
                ->get();
        
        // dd($data);

        return view('bendahara.konfirmasipenyetoran', compact('data'));
    }
}
