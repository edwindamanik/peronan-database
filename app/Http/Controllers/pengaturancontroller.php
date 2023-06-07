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

    public function store(Request $request)
    {
        $user = Auth::user();
        $kabupatenId = $user->kabupaten_id;
        $now = now();
    
        // Validasi apakah nama kelompok pasar sudah pernah diinput sebelumnya
        $existingKelompokPasar = DB::table('market_groups')
            ->where('kelompok_pasar', $request->input('namaKelompokPasar'))
            ->first();
    
        if ($existingKelompokPasar) {
            return back()->with('storeMessagee', 'Kelompok Pasar Sudah pernah ditambahkan');
        }
    
        // Jika validasi berhasil, simpan data kelompok pasar baru
        DB::table('market_groups')->insert([
            'kelompok_pasar' => $request->input('namaKelompokPasar'),
            'kabupaten_id' => $kabupatenId,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    
        return back()->with('storeMessage', 'Kelompok Pasar berhasil ditambahkan');
    }


}