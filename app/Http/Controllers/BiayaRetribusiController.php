<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BiayaRetribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('retribution_fees')
            ->join('market_groups', 'retribution_fees.kelompok_pasar', '=', 'market_groups.id')
            ->join('unit_types', 'retribution_fees.jenis_unit_id', '=', 'unit_types.id')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->where('unit_types.kabupaten_id', $kabupatenId)
            ->orderBy('retribution_fees.created_at', 'desc') // Urutkan berdasarkan 'created_at' secara menurun
            ->orderBy('retribution_fees.updated_at', 'desc')
            ->whereNull('retribution_fees.deleted_at')
            ->select('retribution_fees.*', 'market_groups.id AS kelompok_pasar_id', 'market_groups.kelompok_pasar', 'unit_types.jenis_unit', 'unit_types.panjang', 'unit_types.lebar')
            ->paginate(5);

        $kelompok_pasar = DB::table('market_groups')
            ->where('kabupaten_id', $kabupatenId)
            ->select('market_groups.id', 'market_groups.kelompok_pasar')
            ->get();

        $jenis_unit = DB::table('unit_types')
            ->where('kabupaten_id', $kabupatenId)
            ->select('unit_types.id', 'unit_types.jenis_unit', 'unit_types.panjang', 'unit_types.lebar')
            ->get();

        // dd($data);

        return view('admin.biayaretribusi', compact('data', 'kelompok_pasar', 'jenis_unit'));
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
        try {

            DB::table('retribution_fees')->insert([
                'harga_satuan' => $request->input('hargaSatuan'),
                'satuan' => $request->input('satuan'),
                'harga' => $request->input('harga'),
                'kelompok_pasar' => $request->input('kelompokPasar'),
                'jenis_unit_id' => $request->input('jenisUnit'),
            ]);

            return back()->with('storeMessage', 'Biaya Retribusi berhasil ditambahkan');
        } catch (\Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {

            DB::table('retribution_fees')
                ->where('id', $id)
                ->update([
                    'harga_satuan' => $request->input('hargaSatuan'),
                    'satuan' => $request->input('satuan'),
                    'harga' => $request->input('harga'),
                    'kelompok_pasar' => $request->input('kelompokPasar'),
                    'jenis_unit_id' => $request->input('jenisUnit'),
                ]);

            return back()->with('updateMessage', 'Biaya Retribusi berhasil diperbarui');
        } catch (\Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::table('retribution_fees')->where('id', $id)->delete();

        return back()->with('deleteMessage', 'Biaya Retribusi Berhasil Dihapus');
    }
}