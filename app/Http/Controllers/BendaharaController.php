<?php

namespace App\Http\Controllers;

use App\Models\DailyRetribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Deposit;

class BendaharaController extends Controller
{
    public function confirmDeposit()
    {
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

    public function setorDeposit($depositId)
    {
        // Temukan deposit berdasarkan ID
        $deposit = Deposit::find($depositId);

        if ($deposit) {
            // Perbarui status menjadi "sudah_disetor"
            $deposit->status = 'sudah_setor';
            $deposit->save();

            return redirect()->back()->with('success', 'Status berhasil diubah.');
        } else {
            return redirect()->back()->with('error', 'Deposit tidak ditemukan.');
        }
    }

    public function laptagihan()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('mandatory_retributions')
            ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
            ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
            ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
            ->join('units', 'units.id', '=', 'contracts.unit_id')
            ->join('markets', 'markets.id', '=', 'units.pasar_id')
            ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
            ->get();


        return view('bendahara.laporantagihan', compact('data'));
    }

    public function konfirbatal()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('daily_retributions')
            ->join('markets', 'markets.id', '=', 'daily_retributions.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
            ->join('users', 'users.id', '=', 'market_officers.users_id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->whereNotNull('daily_retributions.bukti_pembatalan')
            // ->where('daily_retributions.status', '=', 0)
            ->select('daily_retributions.*', 'markets.*', 'users.nama')
            ->get();

        return view('bendahara.konfirmasipembatalan', compact('data'));
    }
    public function batalkan($batalId)
    {
        // Temukan deposit berdasarkan ID
        $batal = DailyRetribution::find($batalId);

        if ($batal) {
            // Perbarui status menjadi "sudah_disetor"
            $batal->status = 1;
            $batal->save();

            return redirect()->back()->with('success', 'Status berhasil diubah.');
        } else {
            return redirect()->back()->with('error', 'Deposit tidak ditemukan.');
        }
    }


    public function laporbatal()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('daily_retributions')
            ->join('markets', 'markets.id', '=', 'daily_retributions.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
            ->join('users', 'users.id', '=', 'market_officers.users_id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            // ->where('daily_retributions.status', '=', 1)
            ->select('daily_retributions.*', 'markets.*', 'users.nama')
            ->get();

        return view('bendahara.laporanpembatalan', compact('data'));
    }

    //     public function setorDeposit(Request $request)
// {
//     $depositId = $request->input('depositId');

    //     // Temukan deposit berdasarkan ID
//     $deposit = Deposit::find($depositId);

    //     if ($deposit) {
//         // Perbarui status menjadi "sudah_disetor"
//         $deposit->status = 'sudah_setor';
//         $deposit->save();

    //         return response()->json(['message' => 'Status berhasil diubah.']);
//     } else {
//         return response()->json(['error' => 'Deposit tidak ditemukan.'], 404);
//     }
// }

    public function lapsetor()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('deposits')
            ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('users', 'deposits.users_id', '=', 'users.id')
            ->where('deposits.status', 'sudah_setor')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->select('deposits.*', 'markets.nama_pasar', 'users.nama')
            ->get();

        return view('bendahara.laporansetor', compact('data'));
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

        return Excel::download(new pembatalan($startDate, $endDate, $pasarId), 'data.xlsx');
    }

    public function exportPdflapor(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $pasarId = $request->input('pasar_id');

        $data = Deposit::query()
            ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('users', 'deposits.users_id', '=', 'users.id')
            ->where('deposits.status', 'sudah_setor')
            ->where('markets.id', [$pasarId])
            ->whereBetween('deposits.tanggal_disetor', [$startDate, $endDate])
            ->select('deposits.*', 'markets.nama_pasar', 'users.nama')
            ->get();

        $pdf = SnappyPdf::loadView('bendahara.pdflapor', compact('data'))
             ->setOption('orientation', 'landscape');

        return $pdf->download('laporan_setor.pdf');
    }



    public function exportPdfbatal(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $pasarId = $request->input('pasar_id');

        $data = DailyRetribution::query()
            ->join('markets', 'markets.id', '=', 'daily_retributions.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
            ->join('users', 'users.id', '=', 'market_officers.users_id')
            ->where('daily_retributions.status', 1)
            ->where('markets.id', $pasarId)
            ->whereBetween('daily_retributions.tanggal', [$startDate, $endDate])
            ->select('daily_retributions.*', 'markets.*', 'users.nama')
            ->get();

        $pdf = SnappyPdf::loadView('bendahara.pdfbatal', compact('data'))
             ->setOption('orientation', 'landscape');
                

        return $pdf->download('laporan_batal.pdf');
    }



    public function rekon()
    {


        return view('bendahara.rekonsiliasi');
    }

    public function rekondetail()
    {


        return view('bendahara.rekonsiliasidetail');
    }


    public function retribusi()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('mandatory_retributions')
            ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
            ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
            ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
            ->join('units', 'units.id', '=', 'contracts.unit_id')
            ->join('markets', 'markets.id', '=', 'units.pasar_id')
            ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
            ->get();


        return view('bendahara.retribusiharian', compact('data'));
    }

}