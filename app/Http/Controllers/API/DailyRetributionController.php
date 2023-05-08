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
        $daily_retributions = DailyRetribution::all();
        return response()->json(['data' => $daily_retributions]);
    }

    public function uploadSetoranRetribusi(Request $request, $user_id, $pasar_id)
    {
        $data = $request->json()->all();

        foreach($data as $item) {
            DB::table('daily_retributions')->insert([
                'no_bukti_pembayaran' => $item['no_bukti_pembayaran'],
                'biaya_retribusi' => $item['biaya_retribusi'],
                'tanggal' => $item['tanggal'],
                'bukti_pembatalan' => $item['bukti_pembatalan'],
                'bukti_pembayaran' => $item['bukti_pembayaran'],
                'unit_id' => $item['unit_id'],
                'pasar_id' => $item['pasar_id'],
                'created_at' => now()
            ]);
        }

        $retributions = DB::table('daily_retributions')
            ->select(DB::raw('DATE(created_at) as created_date'), 'pasar_id', DB::raw('SUM(biaya_retribusi) as total_biaya'))
            ->groupBy('created_date', 'pasar_id')
            ->first();

        $tanggal = date('Y-m-d');
        $retributions_bulanan = DB::table('mandatory_retributions')
            ->where('status_pembayaran', 'sudah_dibayar')
            ->where('tanggal_pembayaran', $tanggal)
            ->where('petugas_id', 1)
            ->select(DB::raw('SUM(total_retribusi) as total_biaya'))
            ->first();

        $total_harian_nonharian = $retributions->total_biaya + $retributions_bulanan->total_biaya;

        DB::table('deposits')->insert([
            'jumlah_setoran' => $total_harian_nonharian,
            'penyetoran_melalui' => null,
            'tanggal_penyetoran' => now(),
            'tanggal_disetor' => null,
            'bukti_setoran' => null,
            'status' => 'pending',
            'users_id' => $user_id,
            'pasar_id' => $pasar_id
        ]);

        return response()->json(['data' => 'data berhasil diupload'], Response::HTTP_CREATED);
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
