<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
    
            $accessToken = auth()->user()->createToken('authToken')->plainTextToken;
    
            return redirect('/');
        }  catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function logout() {
        auth()->user()->tokens()->delete();

        return redirect('/login');
    }

    public function register(Request $request) {
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
