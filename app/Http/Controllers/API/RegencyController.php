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
        try {
            $request->validate([
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
    
            $regency = new Regency;
            
            if ($request->hasFile('logo') || $request->hasFile('perda')) {
        
                // save the new file
                $logo = $request->file('logo');
                $logoName = time().'.'.$logo->extension();
                $logo->move(public_path('logo'), $logoName);
    
                $upload_perda = $request->file('upload_perda');
                $upload_perdaName = time().'.'.$upload_perda->extension();
                $upload_perda->move(public_path('upload_perda'), $upload_perdaName);
        
                // update the deposit data with the new file name
                $regency->logo = $logoName;
                $regency->upload_perda = $upload_perdaName;
            }
        
            $regency->nama_dinas = $request->input('nama_dinas');
            $regency->perda = $request->input('perda');
            $regency->kepala_dinas = $request->input('kepala_dinas');
            $regency->provinsi = $request->input('provinsi');
            $regency->kabupaten = $request->input('kabupaten');
            $regency->email_dinas = $request->input('email_dinas');
            $regency->no_telp_dinas = $request->input('no_telp_dinas');
            
            $regency->save();
    
            // get the image URL
            $logoUrl = asset('logo/' . $regency->logo);
            $perdaUrl = asset('upload_perda/' . $regency->upload_perda);
        
            return response()->json([
                'message' => 'Data kabupaten berhasil ditambahkan',
                'logo' => $logoUrl,
                'perda' => $perdaUrl,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
        try{
            $request->validate([
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
    
            if ($request->hasFile('logo') || $request->hasFile('upload_perda')) {
                // delete the old file
                if (\File::exists(public_path('logo/' . $regency->logo))) {
                    \File::delete(public_path('upload_perda/' . $regency->upload_perda));
                }
        
               // save the new file
               $logo = $request->file('logo');
               $logoName = time().'.'.$logo->extension();
               $logo->move(public_path('logo'), $logoName);
    
               $upload_perda = $request->file('upload_perda');
               $upload_perdaName = time().'.'.$upload_perda->extension();
               $upload_perda->move(public_path('upload_perda'), $upload_perdaName);
        
                // update the deposit data with the new file name
                $regency->logo = $logoName;
                $regency->upload_perda = $upload_perdaName;
    
            }
    
            $regency->nama_dinas = $request->input('nama_dinas');
            $regency->perda = $request->input('perda');
            $regency->kepala_dinas = $request->input('kepala_dinas');
            $regency->provinsi = $request->input('provinsi');
            $regency->kabupaten = $request->input('kabupaten');
            $regency->email_dinas = $request->input('email_dinas');
            $regency->no_telp_dinas = $request->input('no_telp_dinas');
            
            $regency->save();
    
            // get the image URL
            $logoUrl = asset('logo/' . $regency->logo);
            $perdaUrl = asset('upload_perda/' . $regency->upload_perda);
    
            return response()->json([
                'message' => 'Data kabupaten berhasil diubah',
                'logo' => $logoUrl,
                'perda' => $perdaUrl,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
