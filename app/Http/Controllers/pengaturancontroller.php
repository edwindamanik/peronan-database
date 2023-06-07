<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regency;
use App\Models\LetterSetting;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Contract;
use DNS2D;
use App\Models\MandatoryRetribution;
use Illuminate\Support\Carbon;

class pengaturancontroller extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        $data = DB::table('regencies')
            ->where('regencies.id', $kabupatenId)
            ->select('regencies.*')
            ->paginate(5);

        return view('admin.pengaturan', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;

        DB::table('regencies')
            ->where('id', $id)
            ->update([
                'nama_dinas' => $request->input('namadinas'),
                'kepala_dinas' => $request->input('kepala'),
                'no_telp_dinas' => $request->input('notelp')
            ]);

        return back()->with('updateMessage', 'Kelompok Pasar berhasil diperbarui');
    }


}