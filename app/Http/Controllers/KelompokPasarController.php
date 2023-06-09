<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class KelompokPasarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
    
        $data = DB::table('market_groups')
            ->where('market_groups.kabupaten_id', $kabupatenId)
            ->whereNull('market_groups.deleted_at')
            ->orderBy('market_groups.created_at', 'desc')
            ->orderBy('market_groups.updated_at', 'desc')
            ->paginate(5);
    
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
        $now = now();
    
        // Validasi apakah nama kelompok pasar sudah pernah diinput sebelumnya
        $existingKelompokPasar = DB::table('market_groups')
            ->where('kelompok_pasar', $request->input('namaKelompokPasar'))
            ->first();
    
        if ($existingKelompokPasar) {
            return back()->with('storeMessagee', 'Kelompok Pasar Sudah pernah ditambahkan');
        }
    
        // Jika validasi berhasil, simpan data kelompok pasar baru
        DB::table('market_groups')->insert([
            'kelompok_pasar' => $request->input('namaKelompokPasar'),
            'kabupaten_id' => $kabupatenId,
            'created_at' => $now,
            'updated_at' => $now
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