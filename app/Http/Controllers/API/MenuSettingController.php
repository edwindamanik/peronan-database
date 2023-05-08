<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\MenuSetting;

class MenuSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menu_setting = MenuSetting::all();
        return response()->json(['data' => $menu_setting]);
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
        $menu_setting = MenuSetting::create([
            'nama_menu' => $request->input('nama_menu'),
            'kabupaten_id' => $request->input('kabupaten_id'),
        ]);

        return response()->json(['data' => $menu_setting], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $menu_setting = MenuSetting::findOrFail($id);

        return response()->json(['data' => $menu_setting]);
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
        $validateMenuSetting = $request->validate([
            'nama_menu' => 'required',
            'kabupaten_id' => 'required',
        ]);

        $menu_setting = MenuSetting::findOrFail($id);
        $menu_setting->update($validateMenuSetting);

        return response()->json(['data' => $menu_setting]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $menu_setting = MenuSetting::findOrFail($id);
        $menu_setting->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
