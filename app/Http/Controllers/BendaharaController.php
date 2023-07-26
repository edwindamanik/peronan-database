<?php

namespace App\Http\Controllers;

use App\Exports\nonharian;
use App\Exports\TagihanExport;
use App\Models\DailyRetribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Deposit;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\laporansetor;
use App\Exports\pembatalan;
use Carbon\Carbon;
use Illuminate\Http\Response;

// use Illuminate\Support\Facades\Request;
// use Symfony\Component\HttpFoundation\Request;
// Use Request;

class BendaharaController extends Controller
{
    //     public function confirmDeposit(Request $request)
// {
//     $user = Auth::user();
//     $kabupatenId = $user->kabupaten_id;

    //     $data = DB::table('deposits')
//         ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
//         ->join('users', 'deposits.users_id', '=', 'users.id')
//         ->join('market_groups', 'market_groups.id', '=', 'markets.kelompok_pasar_id')
//         ->where('market_groups.kabupaten_id', $kabupatenId)
//         ->where('market_groups.kabupaten_id', $kabupatenId)
//         ->select('deposits.*', 'markets.nama_pasar', 'users.nama')
//         ->get();



    //     // dd($data);

    //     return view('bendahara.konfirmasipenyetoran', compact('data'));
// }

    // public function confirmDeposit(Request $request)
    // {
    //     $user = Auth::user();
    //     $kabupatenId = $user->kabupaten_id;

    //     $limit = $request->input('limit', 10);

    //     $data = DB::table('deposits')
    //         ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
    //         ->join('users', 'deposits.users_id', '=', 'users.id')
    //         ->join('market_groups', 'market_groups.id', '=', 'markets.kelompok_pasar_id')
    //         ->where('market_groups.kabupaten_id', $kabupatenId)
    //         ->whereIn('deposits.status', ['disetujui', 'menunggu_konfirmasi'])
    //         ->select('deposits.*', 'markets.nama_pasar', 'users.nama')
    //         ->take($limit)
    //         ->get();

    //     return view('bendahara.konfirmasipenyetoran', compact('data'));
    // }
    public function confirmDeposit(Request $request)
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $limit = $request->input('limit', 10);
        $penyetoranMelalui = $request->input('penyetoran_melalui', null);

        $query = DB::table('deposits')
            ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
            ->join('users', 'deposits.users_id', '=', 'users.id')
            ->join('market_groups', 'market_groups.id', '=', 'markets.kelompok_pasar_id')
            ->whereIn('deposits.status', ['menunggu_konfirmasi', 'belum_setor'])
            ->where('market_groups.kabupaten_id', $kabupatenId);

        if ($penyetoranMelalui === 'tunai') {
            $query->where('deposits.penyetoran_melalui', 'langsung');
        } elseif ($penyetoranMelalui === 'nontunai-va') {
            $query->where('deposits.penyetoran_melalui', 'VA');
        } elseif ($penyetoranMelalui === 'nontunai-qris') {
            $query->where('deposits.penyetoran_melalui', 'QRIS');
        } elseif ($penyetoranMelalui === 'nontunai') {
            $query->whereIn('deposits.penyetoran_melalui', ['VA', 'QRIS']);
        }

        $data = $query->select('deposits.*', 'markets.nama_pasar', 'users.nama')
            ->take($limit)
            ->get();

        // Check if data exists or not
        $isEmpty = $data->isEmpty();

        return view('bendahara.konfirmasipenyetoran', compact('data', 'limit', 'penyetoranMelalui', 'isEmpty'));
    }




    public function updateDeposit()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('deposits')
            ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
            ->join('users', 'deposits.users_id', '=', 'users.id')
            ->join('users AS officer_users', 'market_officers.users_id', '=', 'officer_users.id') // tambahkan join untuk users pada market_officers
            ->whereIn('deposits.status', ['belum_setor', 'pending'])
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->select('deposits.*', 'markets.nama_pasar', 'users.nama', 'market_officers.*', 'officer_users.nama AS officer_name') // tambahkan officer_users.nama untuk mendapatkan nama pengguna pada market_officers
            ->get();
        // dd($data);

        return view('bendahara.konfirmasipenyetoran', compact('data'));
    }



    public function setorDeposit($depositId)
    {
        // Temukan deposit berdasarkan ID
        $deposit = Deposit::find($depositId);

        if ($deposit) {
            // Perbarui status menjadi "sudah_disetor"
            $deposit->status = 'disetujui';
            $deposit->save();

            return redirect('/laporansetor')->with('success', 'Status berhasil diubah.');
        } else {
            return redirect()->back()->with('error', 'Deposit tidak ditemukan.');
        }
    }
    public function tolakdeposit($depositId)
    {
        // Temukan deposit berdasarkan ID
        $deposit = Deposit::find($depositId);

        if ($deposit) {
            // Perbarui status menjadi "sudah_disetor"
            $deposit->status = 'ditolak';
            $deposit->save();

            return redirect()->back()->with('success', 'Status berhasil diubah.');
        } else {
            return redirect()->back()->with('error', 'Deposit tidak ditemukan.');
        }
    }



    public function laptagihan(Request $request)
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $limit = $request->input('limit', 10);
        $penyetoranMelalui = $request->input('penyetoran_melalui', null);

        $query = DB::table('daily_retributions')
            ->join('deposits', 'deposits.id', '=', 'daily_retributions.deposit_id')
            ->join('markets', 'markets.id', '=', 'daily_retributions.pasar_id')
            ->join('market_groups', 'market_groups.id', '=', 'markets.kelompok_pasar_id')
            ->join('users', 'users.id', '=', 'deposits.users_id')
            ->join('units', 'units.id', '=', 'daily_retributions.unit_id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->where('daily_retributions.status', 'sudah_bayar');

        if ($penyetoranMelalui === 'tunai') {
            $query->where('deposits.penyetoran_melalui', 'langsung');
        } elseif ($penyetoranMelalui === 'nontunai') {
            $query->where('deposits.penyetoran_melalui', 'transfer_bank');
        }

        $ret = $query->select('daily_retributions.id', 'daily_retributions.no_bukti_pembayaran', 'markets.nama_pasar', 'users.nama', 'units.no_unit', 'daily_retributions.tanggal', 'daily_retributions.biaya_retribusi', 'markets.id AS pasar_id')
            ->take($limit)
            ->get();

        if ($request->wantsJson()) {
            if ($ret->isEmpty()) {
                $responseData = [
                    'message' => 'No data found.',
                    'data' => [],
                ];
                return response()->json($responseData, Response::HTTP_OK);
            } else {
                $responseData = [
                    'message' => 'Data retrieved successfully.',
                    'data' => $ret,
                ];
                return response()->json($responseData, Response::HTTP_OK);
            }
        }

        // dd($ret);

        return view('bendahara.laporantagihan', compact('ret', 'limit', 'penyetoranMelalui'));
    }


    public function konfirbatal(Request $request)
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $limit = $request->input('limit', 10);

        $data = DB::table('daily_retributions')
            ->join('deposits', 'deposits.id', '=', 'daily_retributions.deposit_id')
            ->join('markets', 'markets.id', '=', 'daily_retributions.pasar_id')
            ->join('market_groups', 'market_groups.id', '=', 'markets.kelompok_pasar_id')
            ->join('users', 'users.id', '=', 'deposits.users_id')
            ->join('units', 'units.id', '=', 'daily_retributions.unit_id')
            ->where('daily_retributions.status', 'batal')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->select('daily_retributions.id', 'daily_retributions.no_bukti_pembayaran', 'markets.nama_pasar', 'users.nama', 'units.no_unit', 'daily_retributions.tanggal', 'daily_retributions.biaya_retribusi', 'daily_retributions.status')
            ->take($limit)
            ->get();



        // dd($data);

        return view('bendahara.konfirmasipembatalan', compact('data', 'limit'));
    }
    public function batalkan($batalId)
    {
        // Temukan deposit berdasarkan ID
        $batal = DB::table('daily_retributions')->where('id', $batalId)->first();

        if ($batal) {
            // Perbarui status menjadi "sudah_disetor"
            DB::table('daily_retributions')
                ->where('id', $batalId)
                ->update(['status' => 'approve_batal']);

            return redirect()->back()->with('success', 'Status berhasil diubah.');
        } else {
            return redirect()->back()->with('error', 'Deposit tidak ditemukan.');
        }
    }

    public function batalkank($batalId)
    {
        // Temukan deposit berdasarkan ID
        $batal = DailyRetribution::find($batalId);

        if ($batal) {
            // Perbarui status menjadi "sudah_disetor"
            $batal->status = 'sudah_bayar';
            $batal->save();

            return redirect()->back()->with('success', 'Status berhasil diubah.');
        } else {
            return redirect()->back()->with('error', 'Deposit tidak ditemukan.');
        }
    }


    public function laporbatal(Request $request)
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $limit = $request->input('limit', 10);

        $data = DB::table('daily_retributions')
            ->join('deposits', 'deposits.id', '=', 'daily_retributions.deposit_id')
            ->join('markets', 'markets.id', '=', 'daily_retributions.pasar_id')
            ->join('market_groups', 'market_groups.id', '=', 'markets.kelompok_pasar_id')
            ->join('users', 'users.id', '=', 'deposits.users_id')
            ->join('units', 'units.id', '=', 'daily_retributions.unit_id')
            ->join('unit_types', 'units.jenis_unit_id', '=', 'unit_types.id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->where('daily_retributions.status', 'approve_batal')
            ->select('daily_retributions.id', 'daily_retributions.no_bukti_pembayaran', 'markets.nama_pasar', 'users.nama', 'units.no_unit', 'unit_types.*', 'daily_retributions.tanggal', 'daily_retributions.biaya_retribusi')
            ->take($limit)
            ->get();



        // dd($data);

        return view('bendahara.laporanpembatalan', compact('data', 'limit'));
    }


    public function lapsetor(Request $request)
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $limit = $request->input('limit', 10);
        $penyetoranMelalui = $request->input('penyetoran_melalui', null);

        $query = DB::table('deposits')
            ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
            ->join('users', 'deposits.users_id', '=', 'users.id')
            ->join('market_groups', 'market_groups.id', '=', 'markets.kelompok_pasar_id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->where('deposits.status', 'disetujui');

        if ($penyetoranMelalui === 'tunai') {
            $query->where('deposits.penyetoran_melalui', 'langsung');
        } elseif ($penyetoranMelalui === 'nontunai') {
            $query->whereIn('deposits.penyetoran_melalui', ['VA', 'Qris']);
        }



        $data = $query->select('deposits.*', 'markets.nama_pasar', 'users.nama')
            ->take($limit)
            ->get();



        // dd($data);

        return view('bendahara.laporansetor', compact('data', 'limit', 'penyetoranMelalui'));
    }
    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $pasarId = $request->input('pasar_id');


        return Excel::download(new laporansetor($startDate, $endDate, $pasarId), 'laporan_setor.xlsx');
    }

    public function exportbatal(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $pasarId = $request->input('pasar_id');

        // return [$startDate, $endDate, $pasarId];

        return Excel::download(new pembatalan($startDate, $endDate, $pasarId), 'data.xlsx');
    }

    public function exportbukti(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $pasarId = $request->input('pasar_id');

        return Excel::download(new TagihanExport($startDate, $endDate, $pasarId), 'file.xlsx');
    }

    public function exportharian(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $pasarId = $request->input('pasar_id');

        return Excel::download(new nonharian($startDate, $endDate, $pasarId), 'file.xlsx');
    }






    public function rekon()
    {


        return view('bendahara.rekonsiliasi');
    }

    public function rekondetail()
    {


        return view('bendahara.rekonsiliasidetail');
    }


    public function retribusi(Request $request)
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $limit = $request->input('limit', 10);
        $marketId = $request->input('market_id');

        $today = Carbon::now()->endOfMonth()->format('Y-m-d');

        $query = DB::table('mandatory_retributions')
            ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
            ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
            ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
            ->join('units', 'units.id', '=', 'contracts.unit_id')
            ->join('letter_settings', 'letter_settings.id', '=', 'contracts.pengaturan_id')
            ->join('markets', 'markets.id', '=', 'units.pasar_id')
            ->where('letter_settings.kabupaten_id', $kabupatenId)
            ->where('mandatory_retributions.status_pembayaran', 'belum_dibayar')
            ->where('mandatory_retributions.jatuh_tempo', $today)
            ->select('mandatory_retributions.no_tagihan', 'users.nama', 'units.no_unit', 'mandatory_retributions.biaya_retribusi', 'mandatory_retributions.jatuh_tempo', 'markets.id as pasar_id', 'markets.nama_pasar');

        if (!empty($marketId)) {
            $query->where('markets.id', $marketId);
        }

        $data = $query->take($limit)
            ->get();

        $markets = DB::table('markets')->pluck('nama_pasar', 'id'); // Mengambil daftar nama pasar

        return view('bendahara.retribusiharian', compact('data', 'limit', 'marketId', 'markets'));
    }

}