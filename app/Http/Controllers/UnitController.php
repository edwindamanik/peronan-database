<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('units')
            ->join('unit_types', 'units.jenis_unit_id', '=', 'unit_types.id')
            ->join('markets', 'units.pasar_id', '=', 'markets.id')
            ->where('unit_types.kabupaten_id', $kabupatenId)
            ->select('units.*', 'markets.nama_pasar', 'unit_types.jenis_unit', 'unit_types.panjang', 'unit_types.lebar')
            ->paginate(5);

        $pasar = DB::table('markets')
                 ->join('market_groups', 'markets.kelompok_pasar_id', '=', 'market_groups.id')
                 ->where('market_groups.kabupaten_id', $kabupatenId)
                 ->select('markets.id', 'markets.nama_pasar')
                 ->get();

        $jenis_unit = DB::table('unit_types')
                      ->where('kabupaten_id', $kabupatenId)
                      ->get();

        // dd($pasar);

        return view('admin.unit', compact('data', 'pasar', 'jenis_unit'));
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
            $user = Auth::user();
            $kabupatenId = $user->kabupaten_id;

            DB::table('units')->insert([
                'no_unit' => $request->input('noUnit'),
                'blok' => $request->input('blok'),
                'deskripsi_lokasi' => $request->input('deskripsi'),
                'pasar_id' => $request->input('pasar'),
                'jenis_unit_id' => $request->input('jenisUnit'),
            ]);

            return back()->with('storeMessage', 'Unit berhasil ditambahkan');
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
        try{
            $user = Auth::user();
            $kabupatenId = $user->kabupaten_id;

            DB::table('units')
                ->where('id', $id)
                ->update([
                    'no_unit' => $request->input('noUnit'),
                    'blok' => $request->input('blok'),
                    'deskripsi_lokasi' => $request->input('deskripsi'),
                    'pasar_id' => $request->input('pasar'),
                    'jenis_unit_id' => $request->input('jenisUnit'),
                ]);

            return back()->with('updateMessage', 'Unit berhasil diperbarui');
        } catch (\Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::table('units')->where('id', $id)->delete();

        return back()->with('deleteMessage', 'Unit Berhasil Dihapus');
    }
}
