<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JenisUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
        $now = now();

        $data = DB::table('unit_types')
            ->where('kabupaten_id', $kabupatenId)
            ->whereNull('unit_types.deleted_at')
            ->orderBy('unit_types.created_at', 'desc') // Urutkan berdasarkan 'created_at' secara menurun
            ->orderBy('unit_types.updated_at', 'desc')

            ->paginate(5);

        // dd($data);

        return view('admin.jenisunit', compact('data'));
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
            $now = now();

            $exitingjenisunit = DB::table('unit_types')
                ->where('kode', $request->input('kode'))
                ->first();

            if ($exitingjenisunit) {
                return back()->with('storeMessagee', 'Jenis Unit Sudah pernah ditambahkan');
            }

            DB::table('unit_types')->insert([
                'kode' => $request->input('kode'),
                'jenis_unit' => $request->input('jenisUnit'),
                'panjang' => $request->input('panjang'),
                'lebar' => $request->input('lebar'),
                'jenis_pembayaran' => $request->input('jenisPembayaran'),
                'kabupaten_id' => $kabupatenId,
                'created_at' => $now,
                'updated_at' => $now
            ]);

            return back()->with('storeMessage', 'Jenis Unit berhasil ditambahkan');
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
            $user = Auth::user();
            $kabupatenId = $user->kabupaten_id;

            DB::table('unit_types')
                ->where('id', $id)
                ->update([
                    'kode' => $request->input('kode'),
                    'jenis_unit' => $request->input('jenisUnit'),
                    'panjang' => $request->input('panjang'),
                    'lebar' => $request->input('lebar'),
                    'jenis_pembayaran' => $request->input('jenisPembayaran'),
                    'kabupaten_id' => $kabupatenId
                ]);

            return back()->with('updateMessage', 'Jenis Unit berhasil diperbarui');
        } catch (\Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::table('unit_types')->where('id', $id)->delete();

        return back()->with('deleteMessage', 'Jenis Unit Berhasil Dihapus');
    }
}