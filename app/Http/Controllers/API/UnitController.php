<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Unit;
use App\Models\Market;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unit = Unit::all();
        return response()->json(['data' => $unit]);
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
            $unit = Unit::create([
                'no_unit' => $request->input('no_unit'),
                'blok' => $request->input('blok'),
                'deskripsi_lokasi' => $request->input('deskripsi_lokasi'),
                'pasar_id' => $request->input('pasar_id'),
                'jenis_unit_id' => $request->input('jenis_unit_id'),
            ]);
    
            return response()->json(['data' => $unit], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit = Unit::findOrFail($id);

        return response()->json(['data' => $unit]);
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
    public function update(Request $request, string $id)
    {
        $validateUnit = $request->validate([
            'no_unit' => 'required',
            'blok' => 'required',
            'deskripsi_lokasi' => 'required',
            'pasar_id' => 'required',
            'jenis_unit_id' => 'required',
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update($validateUnit);

        return response()->json(['data' => $unit]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function getUnit($pasar_id) {

        $kelompok_pasar_id = Market::find($pasar_id);

        $data = DB::table('units')
                ->join('unit_types', 'units.jenis_unit_id', '=', 'unit_types.id')
                ->join('retribution_fees', 'unit_types.id', '=', 'retribution_fees.jenis_unit_id')
                ->select('units.id', 'units.no_unit', 'retribution_fees.harga', 'unit_types.jenis_unit', 'unit_types.panjang', 'unit_types.lebar', 'units.jenis_unit_id')
                ->where('units.pasar_id', $pasar_id)
                ->where('retribution_fees.kelompok_pasar', $kelompok_pasar_id->kelompok_pasar_id)
                ->where('unit_types.jenis_pembayaran', 'harian')
                ->get();
        
        return response()->json(['data' => $data]);
    }

    public function getDetailUnit($pasar_id, $unit_id) {
        $data = DB::table('units')
                ->join('unit_types', 'units.jenis_unit_id', '=', 'unit_types.id')
                ->join('retribution_fees', 'unit_types.id', '=', 'retribution_fees.jenis_unit_id')
                ->select('units.id', 'units.no_unit', 'retribution_fees.harga', 'unit_types.jenis_unit', 'unit_types.panjang', 'unit_types.lebar', 'units.jenis_unit_id')
                ->where('units.pasar_id', $pasar_id)
                ->where('unit_types.jenis_pembayaran', 'harian')
                ->where('units.id', $unit_id)
                ->first();
        
        return response()->json(['data' => $data]);
    }
}
