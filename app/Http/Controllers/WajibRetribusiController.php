<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ObligationRetribution;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

class WajibRetribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
    
        $data = DB::table('obligation_retributions')
            ->join('users', 'obligation_retributions.users_id', '=', 'users.id')
            ->where('users.kabupaten_id', $kabupatenId)
            ->where('users.role', 'wajib_retribusi')
            ->orderBy('obligation_retributions.created_at', 'desc')
            ->orderBy('obligation_retributions.updated_at', 'desc')
            ->select('obligation_retributions.*', 'users.nama')
            ->paginate(5);
    
        $users = DB::table('users')
            ->where('role', 'wajib_retribusi')
            ->where('kabupaten_id', $kabupatenId)
            ->get();
    
        if ($request->wantsJson()) {
            if ($data->isEmpty()) {
                $responseData = [
                    'message' => 'No data found.',
                    'data' => [],
                ];
                return response()->json($responseData, Response::HTTP_OK);
            } else {
                $responseData = [
                    'message' => 'Data retrieved successfully.',
                    'data' => $data,
                    'users' => $users,
                ];
                return response()->json($responseData, Response::HTTP_OK);
            }
        }
    
        return view('admin.wajibretribusi', compact('data', 'users'));
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
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
        try {
            $obligation_retribution = new ObligationRetribution();
            $users = new User();



            if ($request->hasFile('ktp')) {
                $ktp = $request->file('ktp');
                $ktpName = time() . '.' . $ktp->extension();
                $ktp->move(public_path('ktp'), $ktpName);

                // update the deposit data with the new file name
                $obligation_retribution->ktp = $ktpName;
            }

       

            $users->nama = $request->input('nama');
            $users->username = $request->input('username');
            $users->email = $request->input('email');
            $users->role = 'wajib_retribusi';
            $users->kabupaten_id = $kabupatenId;
            $users['password'] = bcrypt($request->password);
            $users->save();

            $obligation_retribution->pekerjaan = $request->input('pekerjaan');
            $obligation_retribution->alamat = 'Silahkan atur alamat'; 
            $obligation_retribution->jenis_usaha = 'Silahkan Jenis usaha'; 
            $obligation_retribution->nik = $request->input('nik');
            $obligation_retribution->users_id = $users->id;
            $obligation_retribution->save();


            return back()->with('storeMessage', 'Wajib Retribusi berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['nik' => 'NIK harus terdiri dari 16 karakter']);
        }
        // dd([
        //     'request' => $request->all(),
        //     'role' => $users->role,
        //     'kabupaten_id' => $users->kabupaten_id,
        // ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        try {

            // DB::table('obligation_retributions')
            //     ->where('id', $id)
            //     ->update([
            //         'ktp' => $request->input('ktp'),
            //         'alamat' => $request->input('alamat'),
            //         'pekerjaan' => $request->input('pekerjaan'),
            //         'jenis_usaha' => $request->input('jenisUsaha'),
            //         'nik' => $request->input('nik'),
            //         'users_id' => $request->input('usersId'),
            //     ]);

            $obligation_retribution = ObligationRetribution::findOrFail($id);

            if ($request->hasFile('ktp')) {
                // delete the old file
                if (\File::exists(public_path('ktp/' . $obligation_retribution->ktp))) {
                    \File::delete(public_path('ktp/' . $obligation_retribution->ktp));
                }

                // save the new file
                $file = $request->file('ktp');
                $fileName = time() . '.' . $file->extension();
                $file->move(public_path('ktp'), $fileName);

                // update the contract data with the new file name
                $obligation_retribution->ktp = $fileName;
            }

            $obligation_retribution->alamat = $request->input('alamat');
            $obligation_retribution->pekerjaan = $request->input('pekerjaan');
            $obligation_retribution->jenis_usaha = $request->input('jenisUsaha');
            $obligation_retribution->nik = $request->input('nik');
            $obligation_retribution->users_id = $request->input('usersId');
            $obligation_retribution->save();

            return back()->with('updateMessage', 'Wajib Retribusi berhasil diperbarui');
        } catch (\Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::table('obligation_retributions')->where('id', $id)->delete();

        return back()->with('deleteMessage', 'Wajib Retribusi Berhasil Dihapus');
    }
}