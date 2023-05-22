<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\MarketGroup;

class MarketGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $market_groups = MarketGroup::all();
        return response()->json(['data' => $market_groups]);
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
            $validateMarketGroup = $request->validate([
                'kelompok_pasar' => 'required',
                'kabupaten_id' => 'required',
            ]);
    
            $market_group = MarketGroup::create($validateMarketGroup);
            return response()->json(['data' => $market_group], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $market_group = MarketGroup::findOrFail($id);

        return response()->json(['data' => $market_group]);
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
        $validateMarketGroup = $request->validate([
            'kelompok_pasar' => 'required',
            'kabupaten_id' => 'required',
        ]);

        $market_group = MarketGroup::findOrFail($id);
        $market_group->update($validateMarketGroup);

        return response()->json(['data' => $market_group]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $market_group = MarketGroup::findOrFail($id);
        $market_group->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
