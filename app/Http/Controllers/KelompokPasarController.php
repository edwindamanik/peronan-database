<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KelompokPasarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('market_groups')
                ->where('market_groups.kabupaten_id', $kabupatenId)
                ->paginate(5);

        // dd($data);

        return view('admin.kelompokpasar', compact('data'));
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
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        DB::table('market_groups')->insert([
            'kelompok_pasar' => $request->input('namaKelompokPasar'),
            'kabupaten_id' => $kabupatenId
        ]);

        return back()->with('storeMessage', 'Kelompok Pasar berhasil ditambahkan');
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
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        DB::table('market_groups')
            ->where('id', $id)
            ->update([
                'kelompok_pasar' => $request->input('namaKelompokPasar'),
                'kabupaten_id' => $kabupatenId
            ]);

        return back()->with('updateMessage', 'Kelompok Pasar berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::table('market_groups')->where('id', $id)->delete();

        return back()->with('deleteMessage', 'Kelompok Pasar Berhasil Dihapus');
    }
}
