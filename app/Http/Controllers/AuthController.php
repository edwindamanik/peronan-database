<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
