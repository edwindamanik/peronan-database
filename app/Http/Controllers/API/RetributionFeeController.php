<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\RetributionFee;

class RetributionFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $retribution_fee = RetributionFee::all();
        return response()->json(['data' => $retribution_fee]);
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
            $retribution_fee = RetributionFee::create([
                'harga_satuan' => $request->input('harga_satuan'),
                'satuan' => $request->input('satuan'),
                'harga' => $request->input('harga'),
                'kelompok_pasar' => $request->input('kelompok_pasar'),
                'jenis_unit_id' => $request->input('jenis_unit_id')
            ]);
    
            return response()->json(['data' => $retribution_fee], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $retribution_fee = RetributionFee::findOrFail($id);

        return response()->json(['data' => $retribution_fee]);
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
        $validateRetributionFee = $request->validate([
            'harga_satuan' => 'required',
            'satuan' => 'required',
            'harga' => 'required',
            'kelompok_pasar' => 'required',
            'jenis_unit_id' => 'required',
        ]);

        $retribution_fee = RetributionFee::findOrFail($id);
        $retribution_fee->update($validateRetributionFee);

        return response()->json(['data' => $retribution_fee]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $retribution_fee = RetributionFee::findOrFail($id);
        $retribution_fee->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
