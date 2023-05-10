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
        try {
            $validateObligationRetribution = $request->validate([
                'ktp' => 'required',
                'alamat' => 'required',
                'pekerjaan' => 'required',
                'jenis_usaha' => 'required',
                'nik' => 'required',
                'users_id' => 'required',
            ]);
    
            $obligation_retribution = new ObligationRetribution;
    
            if($request->hasFile('ktp')) {
                $ktp = $request->file('ktp');
                $ktpName = time().'.'.$ktp->extension();
                $ktp->move(public_path('ktp'), $ktpName);
            
                // update the deposit data with the new file name
                $obligation_retribution->ktp = $ktpName;
            }
    
            $obligation_retribution->alamat = $request->input('alamat');
            $obligation_retribution->pekerjaan = $request->input('pekerjaan');
            $obligation_retribution->jenis_usaha = $request->input('jenis_usaha');
            $obligation_retribution->nik = $request->input('nik');
            $obligation_retribution->users_id = $request->input('users_id');
            $obligation_retribution->save();
    
            // get the image URL
            $ktpUrl = asset('ktp/' . $obligation_retribution->ktp);
        
            return response()->json([
                'message' => 'Data wajib retribusi berhasil ditambahkan',
                'ktp_url' => $ktpUrl,
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

        try {

            $request->validate([
                'ktp' => 'required',
                'alamat' => 'required',
                'pekerjaan' => 'required',
                'jenis_usaha' => 'required',
                'nik' => 'required',
                'users_id' => 'required',
            ]);
        
            $obligation_retribution = Contract::findOrFail($id);
        
            if ($request->hasFile('ktp')) {
                // delete the old file
                if (\File::exists(public_path('ktp/' . $obligation_retribution->ktp))) {
                    \File::delete(public_path('ktp/' . $obligation_retribution->ktp));
                }
        
                // save the new file
                $file = $request->file('ktp');
                $fileName = time().'.'.$file->extension();
                $file->move(public_path('ktp'), $fileName);
        
                // update the contract data with the new file name
                $obligation_retribution->ktp = $fileName;
            }
        
            $obligation_retribution->alamat = $request->input('alamat');
            $obligation_retribution->pekerjaan = $request->input('pekerjaan');
            $obligation_retribution->jenis_usaha = $request->input('jenis_usaha');
            $obligation_retribution->nik = $request->input('nik');
            $obligation_retribution->users_id = $request->input('users_id');
            $obligation_retribution->save();

            // get the image URL
            $fileUrl = asset('ktp/' . $obligation_retribution->ktp);
        
            return response()->json([
                'message' => 'Data wajib retribusi berhasil diupdate',
                'image_url' => $fileUrl,
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
