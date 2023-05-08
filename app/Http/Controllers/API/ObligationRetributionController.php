<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ObligationRetribution;
use Illuminate\Support\Facades\DB;

class ObligationRetributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obligation_retributions = ObligationRetribution::all();
        return response()->json(['data' => $obligation_retributions]);
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
        $validateObligationRetribution = $request->validate([
            'no_telepon' => 'required',
            'ktp' => 'required',
            'alamat' => 'required',
            'pekerjaan' => 'required',
            'jenis_usaha' => 'required',
            'nik' => 'required',
            'users_id' => 'required',
        ]);

        $obligation_retribution = ObligationRetribution::create($validateObligationRetribution);
        return response()->json(['data' => $obligation_retribution], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $obligation_retribution = ObligationRetribution::findOrFail($id);

        return response()->json(['data' => $obligation_retribution]);
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
        $validateObligationRetribution = $request->validate([
            'no_telepon' => 'required',
            'ktp' => 'required',
            'alamat' => 'required',
            'pekerjaan' => 'required',
            'jenis_usaha' => 'required',
            'nik' => 'required',
            'users_id' => 'required',
        ]);

        $obligation_retribution = ObligationRetribution::findOrFail($id);
        $obligation_retribution->update($validateObligationRetribution);

        return response()->json(['data' => $obligation_retribution]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $obligation_retribution = ObligationRetribution::findOrFail($id);
        $obligation_retribution->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function obligation_retribution_by_user($user_id) {
        $data = ObligationRetribution::where('users_id', $user_id)->first();

        return response()->json(['data' => $data]);
    }

    public function getNamaPasar(Request $request, $id) {
        $idUser = $id;

        $data = DB::table('contract')
                ->join('units', 'contracts.unit_id', '=', 'units.id')
                ->join('markets', 'units.pasar_id', '=', 'markets.id')
                ->join('obligation_retributions', 'contracts.wajib_retribusi_id', '=', 'obligation_retributions.id')
                ->select('markets.nama_pasar')
                ->where('obligation_retributions.id', $idUser)
                ->first();

        return response()->json(['nama_pasar' => $data]);
    }
}
