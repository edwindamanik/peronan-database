<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Market;
use App\Models\User;
use App\Models\MarketOfficer;
use Illuminate\Support\Facades\DB;

class MarketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $markets = Market::all();
        return response()->json(['data' => $markets]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $market = Market::create([
                'kode_pasar' => $request->input('kode_pasar'),
                'nama_pasar' => $request->input('nama_pasar'),
                'alamat' => $request->input('alamat'),
                'tahun_berdiri' => $request->input('tahun_berdiri'),
                'tahun_pembangunan' => $request->input('tahun_pembangunan'),
                'koordinat' => $request->input('koordinat'),
                'kondisi_pasar' => $request->input('kondisi_pasar'),
                'luas_lahan' => $request->input('luas_lahan'),
                'pengelola' => $request->input('pengelola'),
                'operasional_pasar' => $request->input('operasional_pasar'),
                'jumlah_pedagang' => $request->input('jumlah_pedagang'),
                'omzet_perbulan' => $request->input('omzet_perbulan'),
                'kelompok_pasar_id' => $request->input('kelompok_pasar_id')
            ]);
    
            return response()->json(['data' => $market], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $market = Market::findOrFail($id);

        return response()->json(['data' => $market]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validateMarket = $request->validate([
            'kode_pasar' => 'required',
            'nama_pasar' => 'required',
            'alamat' => 'required',
            'tahun_berdiri' => 'required',
            'tahun_pembangunan' => 'required',
            'koordinat' => 'required',
            'kondisi_pasar' => 'required',
            'luas_lahan' => 'required',
            'pengelola' => 'required',
            'operasional_pasar' => 'required',
            'jumlah_pedagang' => 'required',
            'omzet_perbulan' => 'required',
            'kabupaten_id' => 'required',
            'kelompok_pasar_id' => 'required',
        ]);

        $market = Market::findOrFail($id);
        $market->update($validateMarket);

        return response()->json(['data' => $market]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $market = Market::findOrFail($id);
        $market->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function pasar_petugas($kabupaten_id, $petugas_id) {
        
        $data = Market::where('kabupaten_id', $kabupaten_id)
            ->where('petugas_pasar_id', $petugas_id)
            ->get();

        return response()->json(['data' => $data]);
    }

    public function choose_market($officer_id) {
        
        $data = DB::table('market_officers')
                ->join('markets', 'market_officers.pasar_id', '=', 'markets.id')
                ->select('markets.id', 'markets.nama_pasar')
                ->where('market_officers.users_id', '=', $officer_id)
                ->get();

        return response()->json(['data' => $data]);
    }
}
