<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MarketOfficer;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
    
            return response('Berhasil menambahkan user baru');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function login(Request $request)
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
    
            return response(['user' => auth()->user(), 'access_token' => $accessToken, 'success' => 'Login success']);
        }  catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response(['message' => 'You have been successfully logged out.']);
    }

    public function updateUser(Request $request, $id) {
        $user = User::findOrFail($id);

        
        $validateUser = $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'role' => 'required',
            'kabupaten_id' => 'required',
        ]);

        if($request->newPassword) {
            
            $passwordBaru = bcrypt($request->newPassword);
            $validateUser['password'] = $passwordBaru;
            $user->update($validateUser);

            return response()->json(['data' => $user]);
            
        } else {

            $user->update($validateUser);
            return response()->json(['data' => $user]);
        }
    }

    public function userDetail($id) {
        $user = User::findOrFail($id);
        $password = $user['password'];

        return response()->json(['data' => $user]);
    }

}
