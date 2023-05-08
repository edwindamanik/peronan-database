<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UnitType;

class UnitTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unit_type = UnitType::all();
        return response()->json(['data' => $unit_type]);
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
        $unit_type = UnitType::create([
            'kode' => $request->input('kode'),
            'jenis_unit' => $request->input('jenis_unit'),
            'panjang' => $request->input('panjang'),
            'lebar' => $request->input('lebar'),
            'jenis_pembayaran' => $request->input('jenis_pembayaran'),
            'kabupaten_id' => $request->input('kabupaten_id')
        ]);

        return response()->json(['data' => $unit_type], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit_type = UnitType::findOrFail($id);

        return response()->json(['data' => $unit_type]);
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
        $validateUnitType = $request->validate([
            'kode' => 'required',
            'jenis_unit' => 'required',
            'panjang' => 'required',
            'lebar' => 'required',
            'jenis_pembayaran' => 'required',
            'kabupaten_id' => 'required',
        ]);

        $unit_type = UnitType::findOrFail($id);
        $unit_type->update($validateUnitType);

        return response()->json(['data' => $unit_type]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit_type = UnitType::findOrFail($id);
        $unit_type->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
