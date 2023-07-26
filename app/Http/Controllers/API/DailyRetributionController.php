<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DailyRetribution;
use App\Models\Deposit;
use App\Models\MandatoryRetribution;
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
        $dataHarian = $request->input('data');
        $totalSetoran = $request->input('setoran');
        $dataToUpdate = $request->input('dataBulanan');

        $depositId = DB::table('deposits')->insertGetId([
            'jumlah_setoran' => $totalSetoran,
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

        foreach ($dataHarian as $item) {
            DB::table('daily_retributions')->insert([
                'no_bukti_pembayaran' => $item['no_bukti_pembayaran'],
                'biaya_retribusi' => $item['biaya_retribusi'],
                'tanggal' => $item['tanggal'],
                'bukti_pembatalan' => $item['bukti_pembatalan'],
                'bukti_pembayaran' => $item['bukti_pembayaran'],
                'status' => $item['status'],
                'deposit_id' => $depositId,
                'unit_id' => $item['unit_id'],
                'pasar_id' => $item['pasar_id'],
                'created_at' => now()
            ]);
        }

        if($dataToUpdate !== null) {
            foreach($dataToUpdate as $data) {
                $id = $data['id'];
                unset($data['id']);

                $mandatory_retribution = MandatoryRetribution::findOrFail($id);

                $bulan_sebelumnya = MandatoryRetribution::where('jatuh_tempo', '<=', $data['jatuh_tempo'])
                            ->where('contract_id', $data['contract_id'])
                            ->get();

                foreach($bulan_sebelumnya as $pembayaran_nonHarian) {
                    $pembayaran_nonHarian->status_pembayaran = 'sudah_dibayar';
                    $pembayaran_nonHarian->metode_pembayaran = 'CASH';
                    $pembayaran_nonHarian->tanggal_pembayaran = Carbon::now();
                    $pembayaran_nonHarian->total_retribusi = $data['total_retribusi'];
                    $pembayaran_nonHarian->petugas_id = $data['petugas_id'];
                    $pembayaran_nonHarian->deposit_id = $depositId;
                    $pembayaran_nonHarian->save();
                }

                // $mandatory_retribution->update($data);

                $count = $data['count'];
                if($count > 0) {
                    $bulan_setelahnya = MandatoryRetribution::where('jatuh_tempo', '>', $data['jatuh_tempo'])
                                ->where('contract_id', $data['contract_id'])
                                ->orderBy('jatuh_tempo', 'asc') 
                                ->take($count) 
                                ->get();

                    foreach($bulan_setelahnya as $pembayaran_nonHarian) {
                        $pembayaran_nonHarian->status_pembayaran = 'sudah_dibayar';
                        $pembayaran_nonHarian->metode_pembayaran = 'CASH';
                        $pembayaran_nonHarian->tanggal_pembayaran = Carbon::now();
                        $pembayaran_nonHarian->total_retribusi = $data['total_retribusi'];
                        $pembayaran_nonHarian->petugas_id = $data['petugas_id'];
                        $pembayaran_nonHarian->deposit_id = $depositId;
                        $pembayaran_nonHarian->save();
                    }
                }
            }
        }

        return response()->json(['data' => 'Berhasil menambahkan retribusi harian dan bulanan dan total setoran']);
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
