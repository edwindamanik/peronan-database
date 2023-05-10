<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MarketOfficer;

class AuthController extends Controller
{
    public function register(Request $request) {
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

        return response(['user' => $user, 'access_token' => $accessToken]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->plainTextToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken, 'success' => 'Login success']);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response(['message' => 'You have been successfully logged out.']);
    }

    public function updateUser(Request $request, $id) {
        $validateUser = $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
            'kabupaten_id' => 'required',
        ]);

        // $validateUser['password'] = bcrypt($request->password);

        $user = User::findOrFail($id);
        $user->update($validateUser);

        return response()->json(['data' => $user]);
        
    }

    public function userDetail($id) {
        $user = User::findOrFail($id);

        return response()->json(['data' => $user]);
    }

}
