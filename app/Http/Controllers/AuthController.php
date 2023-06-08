<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Regency;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function displayLogin() {
        return view('login');
    }

    public function loginCheck(Request $request)
    {
        try {
            $loginData = $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);
    
            if (!auth()->attempt($loginData)) {
                return response(['message' => 'Invalid credentials']);
            }
    
            $user = auth()->user();
            $accessToken = $user->createToken('authToken')->plainTextToken;
    
            if ($user->role === 'bendahara') {
                return redirect('/konfirmasi-setoran');
            } elseif ($user->role === 'admin') {
                return redirect('/pasar');
            } else {
                // Jika peran tidak diketahui, ganti return redirect sesuai kebutuhan
                return redirect('/');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    

    public function logout() {
        auth()->user()->tokens()->delete();

        return redirect('/login');
    }

    public function displayRegister() {
        return view('register');
    }

    public function registerDinas(Request $request) {
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
            $regencyId = $regency->id;
    
            // get the image URL
            $logoUrl = asset('logo/' . $regency->logo);
            $perdaUrl = asset('upload_perda/' . $regency->upload_perda);
        
            return redirect()->route('register-admin', ['regencyID' => $regencyId]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function registerUserView() {
        return view('register-admin');
    }


    public function daftar(Request $request) {
        try {
            $validateUser = $request->validate([
                'nama' => 'required',
                'email' => 'required|email',
                'username' => 'required',
                'password' => 'required',
                'role' => 'required',
                'kabupaten_id' => 'required',
            ]);
    
            $validateUser['password'] = bcrypt($request->password);
            
            $user = User::create($validateUser);
    
            $accessToken = $user->createToken('Token Name')->plainTextToken;
    
            return back()->with('storeMessage', 'Berhasil menambahkan user baru');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    

    public function registerUser(Request $request) {
        try {
            $validateUser = $request->validate([
                'nama' => 'required',
                'email' => 'required|email',
                'username' => 'required',
                'password' => 'required',
                'role' => 'required',
                'kabupaten_id' => 'required',
            ]);

            if($request->input('password') != $request->input('konfirmasi_password')) {
                return 'Password tidak serupa';
            }
    
            $validateUser['password'] = bcrypt($request->password);

            $user = User::create($validateUser);
    
            $accessToken = $user->createToken('Token Name')->plainTextToken;
    
            return redirect('/login');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateUser(Request $request, $id) {
        try {
            $validateUser = $request->validate([
                'nama' => 'required',
                'email' => 'required|email',
                'username' => 'required',
                'password' => 'required',
                'role' => 'required',
                'kabupaten_id' => 'required',
            ]);
            
            $user = User::find($id);
            $user->update($validateUser);
    
            return back()->with('updateMessage', 'Berhasil mengubah user');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function removeUser($id) {
        try {
            DB::table('users')->where('id', $id)->delete();

            return back()->with('deleteMessage', 'User Berhasil Dihapus');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
