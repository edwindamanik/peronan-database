<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\MarketOfficer;

class MarketOfficerController extends Controller
{

    public function index()
    {
        $market_officer = MarketOfficer::all();
        return response()->json(['data' => $market_officer]);
    }

    public function store(Request $request)
    {
        $market_officer = MarketOfficer::create([
            'users_id' => $request->input('users_id'),
            'pasar_id' => $request->input('pasar_id'),
        ]);

        return response()->json(['data' => $market_officer], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $market_officer = MarketOfficer::findOrFail($id);

        return response()->json(['data' => $market_officer]);
    }

    public function update(Request $request, $id)
    {
        $validateMarketOfficer = $request->validate([
            'users_id' => 'required',
            'pasar_id' => 'required',
        ]);

        $market_officer = MarketOfficer::findOrFail($id);
        $market_officer->update($validateMarketOfficer);

        return response()->json(['data' => $market_officer]);
    }

    public function destroy($id)
    {
        $market_officer = MarketOfficer::findOrFail($id);
        $market_officer->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
