<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Deposit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deposits = Deposit::all();
        return response()->json(['data' => $deposits]);
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
            if ($request->hasFile('bukti_setoran')) {
                $file = $request->file('bukti_setoran');
                $name = time();
                $extension = $file->getClientOriginalExtension();
                $newName = $name . '.' . $extension;
                $size = $file->getSize();
                $path = Storage::putFileAs('public', $file, $newName);
        
                $data = [
                    'jumlah_setoran' => $request->input('jumlah_setoran'),
                    'penyetoran_melalui' => $request->input('penyetoran_melalui'),
                    'tanggal_penyetoran' => $request->input('tanggal_penyetoran'),
                    'tanggal_disetor' => $request->input('tanggal_disetor'),
                    'bukti_setoran' => $path,
                    'status' => $request->input('status'),
                    'users_id' => $request->input('users_id'),
                    'pasar_id' => $request->input('pasar_id'),
                ];

                Deposit::create($data);
        
                return response()->json(['message' => 'Data berhasil diupdate']);
            } else {
                return response()->json(['message' => 'No file was uploaded.']);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $deposit = Deposit::findOrFail($id);

        return response()->json(['data' => $deposit]);
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
        try {
            $request->validate([
                'penyetoran_melalui' => 'required',
                'tanggal_disetor' => 'required',
                'bukti_setoran' => 'required|image|mimes:jpeg,png,jpg,gif'
            ]);
        
            $deposit = Deposit::findOrFail($id);
        
            if ($request->hasFile('bukti_setoran')) {
                // delete the old file
                if (\File::exists(public_path('images/' . $deposit->bukti_setoran))) {
                    \File::delete(public_path('images/' . $deposit->bukti_setoran));
                }
        
                // save the new file
                $image = $request->file('bukti_setoran');
                $imageName = time().'.'.$image->extension();
                $image->move(public_path('images'), $imageName);
        
                // update the deposit data with the new file name
                $deposit->bukti_setoran = $imageName;
            }
        
            $deposit->penyetoran_melalui = $request->input('penyetoran_melalui');
            $deposit->tanggal_disetor = $request->input('tanggal_disetor');
            $deposit->save();

            // get the image URL
            $imageUrl = asset('images/' . $deposit->bukti_setoran);
        
            return response()->json([
                'message' => 'Data deposits berhasil diupdate',
                'image_url' => $imageUrl,
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
        $deposit = Deposit::findOrFail($id);
        $deposit->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function getSpesificDeposit($user_id, $pasar_id) {
        $data = DB::table('deposits')
                ->whereNotNull('bukti_setoran')
                ->where('users_id', '=', $user_id)
                ->where('pasar_id', '=', $pasar_id)
                ->get();

        return response()->json(['data' => $data]);
    }

    public function getDetailDeposit($id) {
        $deposit = Deposit::findOrFail($id);

        return response()->json(['data' => $deposit]);
    }

    public function unggahBuktiBayar($user_id, $pasar_id) {
        $data = DB::table('deposits')
                ->where('bukti_setoran', null)
                ->where('status', 'pending')
                ->where('users_id', $user_id)
                ->where('pasar_id', $pasar_id)
                ->get();

        return response()->json(['data' => $data]);
    }

}
