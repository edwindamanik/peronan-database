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
use Illuminate\Http\Response;

class BendaharaController extends Controller
{
    public function confirmDeposit(Request $request)
{
    $user = Auth::user();
    $kabupatenId = $user->kabupaten_id;

    $data = DB::table('deposits')
        ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
        ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
        ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
        ->join('users', 'deposits.users_id', '=', 'users.id')
        ->join('users AS officer_users', 'market_officers.users_id', '=', 'officer_users.id')
        ->whereIn('deposits.status', ['belum_setor', 'menunggu_konfirmasi'])
        ->where('market_groups.kabupaten_id', $kabupatenId)
        ->select('deposits.*', 'markets.nama_pasar', 'users.nama', 'market_officers.*', 'officer_users.nama AS officer_name')
        ->get();

    if ($request->wantsJson()) {
        if ($data->isEmpty()) {
            $responseData = [
                'message' => 'No data found.',
                'data' => [],
            ];
            return response()->json($responseData, Response::HTTP_OK);
        } else {
            $responseData = [
                'message' => 'Data retrieved successfully.',
                'data' => $data,
            ];
            return response()->json($responseData, Response::HTTP_OK);
        }
    }

    return view('bendahara.konfirmasipenyetoran', compact('data'));
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

            return redirect()->back()->with('success', 'Status berhasil diubah.');
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

    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    
    public function laptagihan(Request $request)
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
    
        $ret = DB::table('daily_retributions')
            ->join('units', 'units.id', '=', 'daily_retributions.unit_id')
            ->join('markets', 'markets.id', '=', 'daily_retributions.pasar_id')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
            ->join('users', 'users.id', '=', 'market_officers.users_id')
            ->join('users AS officer', 'market_officers.users_id', '=', 'officer.id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->select('daily_retributions.*', 'markets.*', 'users.nama', 'officer.nama AS officers', 'units.*')
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
    
        return view('bendahara.laporantagihan', compact('ret'));
    }
    

    public function konfirbatal(Request $request)
{
    $user = Auth::user();
    $kabupatenId = $user->kabupaten_id;

    $data = DB::table('daily_retributions')
        ->join('markets', 'markets.id', '=', 'daily_retributions.pasar_id')
        ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
        ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
        ->join('users', 'users.id', '=', 'market_officers.users_id')
        ->where('market_groups.kabupaten_id', $kabupatenId)
        ->where('daily_retributions.status', '=',  '1')
        ->select('daily_retributions.*', 'markets.*', 'users.nama')
        ->get();

    if ($request->wantsJson()) {
        if ($data->isEmpty()) {
            $responseData = [
                'message' => 'No data found.',
                'data' => [],
            ];
            return response()->json($responseData, Response::HTTP_OK);
        } else {
            $responseData = [
                'message' => 'Data retrieved successfully.',
                'data' => $data,
            ];
            return response()->json($responseData, Response::HTTP_OK);
        }
    }

    return view('bendahara.konfirmasipembatalan', compact('data'));
}
    public function batalkan($batalId)
    {
        // Temukan deposit berdasarkan ID
        $batal = DailyRetribution::find($batalId);

        if ($batal) {
            // Perbarui status menjadi "sudah_disetor"
            $batal->status = '2';
            $batal->save();

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
            $batal->status = '3';
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

    $data = DB::table('daily_retributions')
        ->join('markets', 'markets.id', '=', 'daily_retributions.pasar_id')
        ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
        ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
        ->join('users', 'users.id', '=', 'market_officers.users_id')
        ->where('market_groups.kabupaten_id', $kabupatenId)
        ->where('daily_retributions.status', '=', '2')
        ->select('daily_retributions.*', 'markets.*', 'users.nama')
        ->get();

    if ($request->wantsJson()) {
        if ($data->isEmpty()) {
            $responseData = [
                'message' => 'No data found.',
                'data' => [],
            ];
            return response()->json($responseData, Response::HTTP_OK);
        } else {
            $responseData = [
                'message' => 'Data retrieved successfully.',
                'data' => $data,
            ];
            return response()->json($responseData, Response::HTTP_OK);
        }
    }

    return view('bendahara.laporanpembatalan', compact('data'));
}


public function lapsetor(Request $request)
{
    $user = Auth::user();
    $kabupatenId = $user->kabupaten_id;

    $data = DB::table('deposits')
        ->join('markets', 'markets.id', '=', 'deposits.pasar_id')
        ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
        ->join('users', 'deposits.users_id', '=', 'users.id')
        ->join('market_officers', 'market_officers.pasar_id', '=', 'markets.id')
        ->join('users AS officer_users', 'market_officers.users_id', '=', 'officer_users.id')
        ->where('deposits.status', 'disetujui')
        ->where('market_groups.kabupaten_id', $kabupatenId)
        ->select('deposits.*', 'markets.nama_pasar', 'users.nama', 'officer_users.nama AS officer_name')
        ->get();

    if ($request->wantsJson()) {
        if ($data->isEmpty()) {
            $responseData = [
                'message' => 'No data found.',
                'data' => [],
            ];
            return response()->json($responseData, Response::HTTP_OK);
        } else {
            $responseData = [
                'message' => 'Data retrieved successfully.',
                'data' => $data,
            ];
            return response()->json($responseData, Response::HTTP_OK);
        }
    }

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

    $data = DB::table('mandatory_retributions')
        ->join('contracts', 'contracts.id', '=', 'mandatory_retributions.contract_id')
        ->join('obligation_retributions', 'obligation_retributions.id', '=', 'contracts.wajib_retribusi_id')
        ->join('users', 'users.id', '=', 'obligation_retributions.users_id')
        ->join('units', 'units.id', '=', 'contracts.unit_id')
        ->join('markets', 'markets.id', '=', 'units.pasar_id')
        ->where('mandatory_retributions.status_pembayaran','=', 'belum_dibayar')
        ->select('mandatory_retributions.*', 'contracts.*', 'obligation_retributions.*', 'units.*', 'markets.*', 'users.*')
        ->get();

    if ($request->wantsJson()) {
        if ($data->isEmpty()) {
            $responseData = [
                'message' => 'No data found.',
                'data' => [],
            ];
            return response()->json($responseData, Response::HTTP_OK);
        } else {
            $responseData = [
                'message' => 'Data retrieved successfully.',
                'data' => $data,
            ];
            return response()->json($responseData, Response::HTTP_OK);
        }
    }

    return view('bendahara.retribusiharian', compact('data'));
}

}