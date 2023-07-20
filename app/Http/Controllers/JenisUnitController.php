<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class JenisUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
        $now = now();
    
        $data = DB::table('unit_types')
            ->where('kabupaten_id', $kabupatenId)
            ->whereNull('unit_types.deleted_at')
            ->orderBy('unit_types.created_at', 'desc')
            ->orderBy('unit_types.updated_at', 'desc')
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
//    public function store(Request $request)
// {
//     try {
//         $user = Auth::user();
//         $kabupatenId = $user->kabupaten_id;
//         $now = now();

//         $panjangArray = $request->input('panjang');
//         $lebarArray = $request->input('lebar');

//         // Lakukan validasi jika perlu
//         // ...

//         // Loop melalui pasangan panjang dan lebar untuk menyimpan data ke database
//         foreach ($panjangArray as $key => $panjang) {
//             $lebar = $lebarArray[$key];

//             DB::table('unit_types')->insert([
//                 'kode' => $request->input('kode'),
//                 'jenis_unit' => $request->input('jenisUnit'),
//                 'panjang' => $panjang,
//                 'lebar' => $lebar,
//                 'jenis_pembayaran' => $request->input('jenisPembayaran'),
//                 'kabupaten_id' => $kabupatenId,
//                 'created_at' => $now,
//                 'updated_at' => $now
//             ]);
//         }

//         return back()->with('storeMessage', 'Data berhasil ditambahkan ke database');
//     } catch (\Exception $e) {
//         return response()->json(['errorMessage' => $e->getMessage()]);
//     }
// }

public function store(Request $request)
{
    try {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
        $now = now();

        $kode = $request->input('kode');
        $jenisUnit = $request->input('jenisUnit');
        $jenisPembayaran = $request->input('jenisPembayaran');
        $panjangArray = $request->input('panjang');
        $lebarArray = $request->input('lebar');

        // Loop melalui pasangan panjang dan lebar untuk menyimpan data ke database
        foreach ($panjangArray as $key => $panjang) {
            $lebar = $lebarArray[$key];

            DB::table('unit_types')->insert([
                'kode' => $kode,
                'jenis_unit' => $jenisUnit,
                'panjang' => $panjang,
                'lebar' => $lebar,
                'jenis_pembayaran' => $jenisPembayaran,
                'kabupaten_id' => $kabupatenId,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        return back()->with('storeMessage', 'Jenis Unit Berhasil Ditambahkan');
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