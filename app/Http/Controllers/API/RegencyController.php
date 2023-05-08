<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Regency;

class RegencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regencies = Regency::all();
        return response()->json(['data' => $regencies]);
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
        $validateRegency = $request->validate([
            'nama_dinas' => 'required',
            'logo' => 'required',
            'perda' => 'required',
            'kepala_dinas' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'email_dinas' => 'required|email',
            'no_telp_dinas' => 'required',
            'upload_perda' => 'required',
        ]);

        $regency = Regency::create($validateRegency);
        return response()->json(['data' => $regency], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $regency = Regency::findOrFail($id);

        return response()->json(['data' => $regency]);
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
        $validateRegency = $request->validate([
            'nama_dinas' => 'required',
            'logo' => 'required',
            'perda' => 'required',
            'kepala_dinas' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'email_dinas' => 'required|email',
            'no_telp_dinas' => 'required',
            'upload_perda' => 'required',
        ]);

        $regency = Regency::findOrFail($id);
        $regency->update($validateRegency);

        return response()->json(['data' => $regency]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $regency = Regency::findOrFail($id);
        $regency->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
