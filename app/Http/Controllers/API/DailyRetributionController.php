<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DailyRetribution;
use App\Models\Deposit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DailyRetributionController extends Controller
{
    public function index()
    {
        $daily_retributions = DB::table('daily_retributions')->get();
        return response()->json(['data' => $daily_retributions]);
    }
    
    public function uploadSetoranRetribusi(Request $request, $user_id, $pasar_id)
    {
        $data = $request->json()->all();

        $total_harian_nonharian = 0;

        foreach($data as $item) {
            $total_harian_nonharian += $item['biaya_retribusi'];
        }

        $tanggal = date('Y-m-d');
        $retributions_bulanan = DB::table('mandatory_retributions')
            ->where('status_pembayaran', 'sudah_dibayar')
            ->where('tanggal_pembayaran', $tanggal)
            ->where('petugas_id', $user_id)
            ->select(DB::raw('SUM(biaya_retribusi) as total_biaya'))
            ->first();

        $total_harian_nonharian += $retributions_bulanan->total_biaya;

        $depositId = DB::table('deposits')->insertGetId([
            'jumlah_setoran' => $total_harian_nonharian,
            'penyetoran_melalui' => null,
            'tanggal_penyetoran' => now(),
            'tanggal_disetor' => null,
            'bukti_setoran' => null,
            'status' => 'belum_setor',
            'alasan_tidak_setor' => null,
            'users_id' => $user_id,
            'wajib_retribusi_id' => null,
            'pasar_id' => $pasar_id
        ]);

        foreach ($data as $item) {
            DB::table('daily_retributions')->insert([
                'no_bukti_pembayaran' => $item['no_bukti_pembayaran'],
                'biaya_retribusi' => $item['biaya_retribusi'],
                'tanggal' => $item['tanggal'],
                'bukti_pembatalan' => $item['bukti_pembatalan'],
                'bukti_pembayaran' => $item['bukti_pembayaran'],
                'status' => 'sudah_bayar',
                'deposit_id' => $depositId,
                'unit_id' => $item['unit_id'],
                'pasar_id' => $item['pasar_id'],
                'created_at' => now()
            ]);
        }

        return response()->json(['data' => 'Berhasil melakukan penyetoran']);
    }

    public function show($id)
    {
        $daily_retribution = DailyRetribution::findOrFail($id);

        return response()->json(['data' => $daily_retribution]);
    }

    public function update(Request $request, $id)
    {
        $validateDailyRetribution = $request->validate([
            'no_bukti_pembayaran' => 'required',
            'biaya_retribusi' => 'required',
            'tanggal' => 'required',
            'bukti_pembatalan' => 'required',
            'bukti_pembayaran' => 'required',
            'unit_id' => 'required',
            'pasar_id' => 'required',
        ]);

        $daily_retribution = DailyRetribution::findOrFail($id);
        $daily_retribution->update($validateDailyRetribution);

        return response()->json(['data' => $daily_retribution]);
    }

    public function destroy($id)
    {
        $daily_retribution = DailyRetribution::findOrFail($id);
        $daily_retribution->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function daftarBuktiBayar() {
        $data = DB::table('daily_retributions')
                ->whereDate('created_at', Carbon::today())
                ->get();
                
        return response()->json(['data' => $data]);
        
    }
}
