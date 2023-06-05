<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Market;

class PasarController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('markets')
            ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->orderBy('markets.created_at', 'desc') // Urutkan berdasarkan 'created_at' secara menurun
            ->orderBy('markets.updated_at', 'desc') 
            ->whereNull('markets.deleted_at')
            ->orderBy('market_groups.created_at', 'desc') // Urutkan berdasarkan 'created_at' secara menurun
            ->orderBy('market_groups.updated_at', 'desc')
            ->select('markets.*', 'market_groups.kelompok_pasar')
            ->paginate(5);

        $petugas = DB::table('users')
            ->where('users.role', 'petugas')
            ->where('kabupaten_id', $kabupatenId)
            ->get();

        $kelompok_pasar = DB::table('market_groups')
            ->where('kabupaten_id', $kabupatenId)
            ->get();

        foreach ($data as $item) {
            $petugas_pasar = DB::table('market_officers')
                ->join('users', 'users.id', '=', 'market_officers.users_id')
                ->select('users.nama')
                ->where('market_officers.pasar_id', $item->id)
                ->get();

            $item->petugas = $petugas_pasar;
        }

        // dd($petugas);

        return view('admin.pasar', compact('data', 'petugas', 'kelompok_pasar'));
    }

    public function store(Request $request)
    {
        try {
            $market = Market::create([
                'kode_pasar' => $request->input('kodePasar'),
                'nama_pasar' => $request->input('namaPasar'),
                'alamat' => $request->input('alamatPasar'),
                'tahun_berdiri' => $request->input('tahunBerdiri'),
                'tahun_pembangunan' => $request->input('tahunPembangunan'),
                'koordinat' => $request->input('koordinatPasar'),
                'kondisi_pasar' => $request->input('kondisiPasar'),
                'luas_lahan' => $request->input('luasLahan'),
                'pengelola' => $request->input('pengelola'),
                'operasional_pasar' => $request->input('operasionalPasar'),
                'jumlah_pedagang' => $request->input('jumlahPedagang'),
                'omzet_perbulan' => $request->input('omzetPerbulan'),
                'kelompok_pasar_id' => $request->input('kelompokPasar')
            ]);

            return redirect('/pasar');
        } catch (\Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        DB::table('markets')->where('id', $id)->delete();

        return back()->with('deleteMessage', 'Pasar Berhasil Dihapus');
    }
}