<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenyetoranController extends Controller
{
    public function indexreq()
    {
        $data = DB::table('penyetorans')
        ->join('pasars', 'penyetorans.pasar_id', '=', 'pasars.id')
        ->join('users', 'penyetorans.users_id', '=', 'users.id')
        ->select('pasars.nama_pasar','users.nama','penyetorans.*')
        ->orderBy('penyetorans.tanggal_penyetoran','DESC')
        ->get();

        return $data;
    }
}
