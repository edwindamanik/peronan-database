<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['data' => $users]);
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
        $validateUser = $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
            'kabupaten_id' => 'required',
        ]);
        
        $validateUser['password'] = Hash::make($validateUser['password']);
        $user = User::create($validateUser);
        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(['data' => $user, 'access_token' => $accessToken], Response::HTTP_CREATED);
    }

    public function logout(Request $request) {
        auth()->user()->token()->revoke();
        return response(['message' => 'You have been successfully logged out.']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json(['data' => $user]);
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
        $user = User::findOrFail($id);

        $validateUser = $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
            'kabupaten_id' => 'required',
        ]);

        if(isset($validateUser['password'])) {
            $validateUser['password'] = Hash::make($validateUser['password']);
        } else {
            unset($validateUser['password']);
        }

        $user->update($validateUser);

        return response()->json(['data' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
